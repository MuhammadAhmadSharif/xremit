<?php
namespace App\Constants;
use Illuminate\Support\Str;

class PaymentGatewayConst {

    const AUTOMATIC             = "AUTOMATIC";
    const MANUAL                = "MANUAL";
    const ADDMONEY              = "Add Money";
    const REMITTANCE_MONEY      = "Remittance Gateway";
    const MONEYOUT              = "Money Out";
    const ACTIVE                =  true;
    const CRYPTO                    = "CRYPTO";

    const ASSET_TYPE_WALLET         = "WALLET";

    const TYPESENDREMITTANCE    = "SEND-REMITTANCE";
    const CRYPTO_NATIVE             = "CRYPTO_NATIVE";
    
    const STATUSSUCCESS             = 1;
    const STATUSPENDING             = 2;
    const STATUSHOLD                = 3;
    const STATUSREJECTED            = 4;
    const STATUSWAITING             = 5;

    const APP           = "APP";
    const SEND          = "SEND";
    const RECEIVED      = "RECEIVED";
    const MANUA_GATEWAY = 'manual';


    const NOT_USED  = "NOT-USED";
    const USED      = "USED";
    const SENT      = "SENT";


    const PAYPAL                    = 'paypal';
    const G_PAY                     = 'gpay';
    const COIN_GATE                 = 'coingate';
    const QRPAY                     = 'qrpay';
    const TATUM                     = 'tatum';
    const STRIPE                    = 'stripe';
    const FLUTTERWAVE               = 'flutterwave';
    const SSLCOMMERZ                = 'sslcommerz';
    const RAZORPAY                  = 'razorpay';
    const PERFECT_MONEY             = 'perfect-money';
    const PAGADITO                  = 'pagadito';
    const PAYSTACK                  = "paystack";

    const PROJECT_CURRENCY_MULTIPLE = "PROJECT_CURRENCY_MULTIPLE";
    const PROJECT_CURRENCY_SINGLE   = "PROJECT_CURRENCY_SINGLE";
    const CALLBACK_HANDLE_INTERNAL  = "CALLBACK_HANDLE_INTERNAL";

    //transaction type

    const TRANSACTION_TYPE_BANK     = "Bank Transfer";
    const TRANSACTION_TYPE_MOBILE   = "Mobile Money";

    const ENV_SANDBOX       = "SANDBOX";
    const ENV_PRODUCTION    = "PRODUCTION";

    public static function add_money_slug() {
        return Str::slug(self::ADDMONEY);
    }
    public static function remittance_money_slug() {
        return Str::slug(self::REMITTANCE_MONEY);
    }


    public static function money_out_slug() {
        return Str::slug(self::MONEYOUT);
    }

    public static function register($alias = null) {
        $gateway_alias  = [
            self::PAYPAL        => "paypalInit",
            self::G_PAY         => "gpayInit",
            self::COIN_GATE     => "coinGateInit",
            self::QRPAY         => "qrpayInit",
            self::TATUM         => 'tatumInit',
            self::STRIPE        => 'stripeInit',
            self::FLUTTERWAVE   => 'flutterwaveInit',
            self::SSLCOMMERZ    => 'sslCommerzInit',
            self::RAZORPAY      => 'razorpayInit',
            self::PERFECT_MONEY => 'perfectMoneyInit',
            self::PAGADITO      => 'pagaditoInit',
            self::PAYSTACK      => 'paystackInit'
        ];

        if($alias == null) {
            return $gateway_alias;
        }

        if(array_key_exists($alias,$gateway_alias)) {
            return $gateway_alias[$alias];
        }
        return "init";
    }
    public static function registerGatewayRecognization() {
        return [
            'isGpay'        => self::G_PAY,
            'isPaypal'      => self::PAYPAL,
            'isCoinGate'    => self::COIN_GATE,
            'isQrpay'       => self::QRPAY,
            'isTatum'       => self::TATUM,
            'isStripe'      => self::STRIPE,
            'isFlutterwave' => self::FLUTTERWAVE,
            'isSslCommerz'  => self::SSLCOMMERZ,
            'isRazorpay'    => self::RAZORPAY,
            'isPerfectMoney'    => self::PERFECT_MONEY,
            'isPagadito'        => self::PAGADITO,
            'isPaystack'        => self::PAYSTACK
        ];
    }
    public static function apiAuthenticateGuard() {
        return [
            'api'   => 'web',
        ];
    }

    public static function registerRedirection() {
        return [
            'web'       => [
                'return_url'    => 'user.send.remittance.payment.success',
                'cancel_url'    => 'user.send.remittance.payment.cancel',
                'callback_url'  => 'user.send.remittance.payment.callback',
                'btn_pay'       => 'user.send.remittance.payment.btn.pay',
            ],
            'api'       => [
                'return_url'    => 'api.user.send.remittance.payment.success',
                'cancel_url'    => 'api.user.send.remittance.payment.cancel',
                'callback_url'  => 'user.send.remittance.payment.callback',
                'btn_pay'       => 'api.user.send.remittance.payment.btn.pay',
            ],
        ];
    }
}
