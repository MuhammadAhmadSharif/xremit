<?php

namespace App\Traits\PaymentGateway;

use Exception;
use Illuminate\Support\Str;
use App\Models\TemporaryData;
use App\Constants\PaymentGatewayConst;
use App\Http\Helpers\Pagadito;
use App\Http\Helpers\PaymentGateway;
use Illuminate\Support\Facades\Config;

trait PagaditoTrait {

    private $pagadito_gateway_credentials;

    public function pagaditoInit($output = null) {
        
        if(!$output) $output = $this->output;
        $credentials = $this->getPagaditoCredentials($output);
        $this->pagaditoSetSecreteKey($credentials);
        
        return $this->pagaditoCreateOrder($credentials,$output);

    }

    public function getPagaditoCredentials($output) {
        $gateway = $output['gateway'] ?? null;
        if(!$gateway) throw new Exception(__("Payment gateway not available"));

        $uid_sample = ['UID','uid','u_id'];
        $wsk_sample = ['WSK','wsk','w_sk'];
        $base_url_sample = ['Base URL','base_url','base-url', 'base url'];

        $uid =  PaymentGateway::getValueFromGatewayCredentials($gateway,$uid_sample);
        $wsk =  PaymentGateway::getValueFromGatewayCredentials($gateway,$wsk_sample);
        $base_url =  PaymentGateway::getValueFromGatewayCredentials($gateway,$base_url_sample);
        $mode = $gateway->env;

        $gateway_register_mode = [
            PaymentGatewayConst::ENV_SANDBOX => PaymentGatewayConst::ENV_SANDBOX,
            PaymentGatewayConst::ENV_PRODUCTION => PaymentGatewayConst::ENV_PRODUCTION,
        ];

        if(array_key_exists($mode,$gateway_register_mode)) {
            $mode = $gateway_register_mode[$mode];
        }else {
            $mode = PaymentGatewayConst::ENV_SANDBOX;
        }

        $credentials = (object) [
            'uid'     => $uid,
            'wsk'     => $wsk,
            'base_url'     => $base_url,
            'mode'          => $mode,
        ];

        $this->pagadito_gateway_credentials = $credentials;

        return $credentials;
    }

    public function pagaditoSetSecreteKey($credentials){
        Config::set('pagadito.UID',$credentials->uid);
        Config::set('pagadito.WSK',$credentials->wsk);
        if($credentials->mode == "SANDBOX"){
            Config::set('pagadito.SANDBOX',true);
        }else{
            Config::set('pagadito.SANDBOX',false);
        }

    }

    public function pagaditoCreateOrder($credentials, $output) {
        if(!$output) $output = $this->output;
        $uid = $credentials->uid;
        $wsk = $credentials->wsk;
        $mode = $credentials->mode;
        $Pagadito = new Pagadito($uid,$wsk,$credentials,$output['amount']->sender_cur_code);
        $Pagadito->config( $credentials,$output['amount']->sender_cur_code);
        
        if ($mode == "SANDBOX") {
            $Pagadito->mode_sandbox_on();
        }
        if ($Pagadito->connect()) {
            
            $Pagadito->add_detail(1,"Please Pay For  Transfer Money", $output['amount']->total_amount);
            
            $Pagadito->set_custom_param("param1", "Valor de param1");
            $Pagadito->set_custom_param("param2", "Valor de param2");
            $Pagadito->set_custom_param("param3", "Valor de param3");
            $Pagadito->set_custom_param("param4", "Valor de param4");
            $Pagadito->set_custom_param("param5", "Valor de param5");
            $Pagadito->enable_pending_payments();
            $getUrls = (object)$Pagadito->exec_trans($Pagadito->get_rs_code());
            
            
            if($getUrls->code == "PG1002" ){
                
                $parts = parse_url($getUrls->value);
                parse_str($parts['query'], $query);
                // Extract the token value
                if (isset($query['token'])) {
                    $tokenValue = $query['token'];
                } else {
                    $tokenValue = '';
                }
                $this->pagaditioJunkInsert($getUrls,$tokenValue);
                if(request()->expectsJson()) { // API Response
                    $this->output['redirection_response']   = $getUrls;
                    $this->output['redirect_links']         = [];
                    $this->output['redirect_url']           = $getUrls->value;
                    return $this->get();
                }

                return redirect($getUrls->value);

            }
            
            $ern = rand(1000, 2000);
            if (!$Pagadito->exec_trans($ern)) {
                switch($Pagadito->get_rs_code())
                {
                    case "PG2001":
                        /*Incomplete data*/
                    case "PG3002":
                        /*Error*/
                    case "PG3003":
                        /*Unregistered transaction*/
                    case "PG3004":
                        /*Match error*/
                    case "PG3005":
                        /*Disabled connection*/
                    default:
                        throw new Exception($Pagadito->get_rs_code().": ".$Pagadito->get_rs_message());
                        break;
                }
            }
            return redirect($Pagadito->exec_trans($Pagadito->get_rs_code()));
        } else {

            switch($Pagadito->get_rs_code())
            {
                case "PG2001":
                    /*Incomplete data*/
                case "PG3001":
                    /*Problem connection*/
                case "PG3002":
                    /*Error*/
                case "PG3003":
                    /*Unregistered transaction*/
                case "PG3005":
                    /*Disabled connection*/
                case "PG3006":
                    /*Exceeded*/
                default:
                    throw new Exception($Pagadito->get_rs_code().": ".$Pagadito->get_rs_message());
                    break;
            }

        }

        throw new Exception(__("Something went wrong! Please try again"));
    }

    public function pagaditioJunkInsert($response, $identifier_token) {
        $output = $this->output;
        $output['transaction_id']  = generate_unique_string("transactions","trx_id",16);

        $data = [
            'gateway'               => $output['gateway']->id,
            'currency'      => [
                'id'        => $output['currency']->id,
                'alias'     => $output['currency']->alias
            ],
            'payment_method'=> $output['currency'],
            'amount'                => json_decode(json_encode($output['amount']),true),
            'response'              => $response,
            'creator_table'         => auth()->guard(get_auth_guard())->user()->getTable(),
            'creator_id'            => auth()->guard(get_auth_guard())->user()->id,
            'creator_guard'         => get_auth_guard(),
            'user_record'           => $output['form_data']['identifier'],
        ];

        // $this->deletePreTemp($output['form_data']['identifier']);
        return TemporaryData::create([
            'type'          => PaymentGatewayConst::PAGADITO,
            'identifier'    => $identifier_token,
            'data'          => $data,
        ]);

    }

    public function pagaditoSuccess($output = null) {

        $output['capture']              = $output['tempData']['data']->response ?? "";
        $output['record_handler']       = 'insertRecordWeb';
        $status = global_const()::REMITTANCE_STATUS_PENDING;
        // need to insert new transaction in database
        try{
            $transaction_response = $this->createTransaction($output,$status);
        }catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $transaction_response;
    }

    public static function isPagadito($gateway) {
        $search_keyword = ['pagadito','pagadito gateway','pagadito payment','pagadito fait gateway','gateway pagadito'];
        $gateway_name = $gateway->name;

        $search_text = Str::lower($gateway_name);
        $search_text = preg_replace("/[^A-Za-z0-9]/","",$search_text);
        foreach($search_keyword as $keyword) {
            $keyword = Str::lower($keyword);
            $keyword = preg_replace("/[^A-Za-z0-9]/","",$keyword);
            if($keyword == $search_text) {
                return true;
                break;
            }
        }
        return false;
    }

}
