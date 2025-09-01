<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\TransactionSetting;
use Illuminate\Database\Seeder;

class TransactionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('admin_id' => '1','slug' => 'bank-transfer','title' => 'Bank Transfer','fixed_charge' => '2.00','percent_charge' => '1.00','min_limit' => '0.00','max_limit' => '50000.00','monthly_limit' => '50000.00','daily_limit' => '5000.00','intervals' => '[{"min_limit":"1","max_limit":"100","fixed":"1","percent":"2"},{"min_limit":"101","max_limit":"200","fixed":"2","percent":"1"},{"min_limit":"201","max_limit":"5000","fixed":"5","percent":"1"}]','status' => '1','feature_text' => '<p><i class="las la-circle"></i> You could save up to 1.5 USD</p>','created_at' => NULL,'updated_at' => NULL),
            array('admin_id' => '1','slug' => 'mobile_money','title' => 'Mobile Money','fixed_charge' => '2.00','percent_charge' => '1.00','min_limit' => '0.00','max_limit' => '50000.00','monthly_limit' => '50000.00','daily_limit' => '5000.00','intervals' => '[{"min_limit":"1","max_limit":"100","fixed":"1","percent":"2"},{"min_limit":"101","max_limit":"200","fixed":"2","percent":"1"},{"min_limit":"201","max_limit":"5000","fixed":"5","percent":"1"}]','status' => '1','feature_text' => '<p><i class="las la-circle"></i> You could save up to 1.5 USD</p><p><i class="las la-circle"></i> Should arrive in 2 hours</p>','created_at' => NULL,'updated_at' => '2023-08-02 11:25:04'),
            array('admin_id' => '1','slug' => 'cash-pickup','title' => 'Cash Pickup','fixed_charge' => '2.00','percent_charge' => '1.00','min_limit' => '0.00','max_limit' => '50000.00','monthly_limit' => '50000.00','daily_limit' => '5000.00','intervals' => '[{"min_limit":"1","max_limit":"100","fixed":"1","percent":"2"},{"min_limit":"101","max_limit":"200","fixed":"2","percent":"1"},{"min_limit":"201","max_limit":"5000","fixed":"5","percent":"1"}]','status' => '1','feature_text' => '<p><i class="las la-circle"></i> You could save up to 1.5 USD</p><p><i class="las la-circle"></i> Should arrive in 2 hours</p>','created_at' => NULL,'updated_at' => '2023-08-02 11:25:04'),
        );

        foreach($data as $item){
            $transactions   = TransactionSetting::where('slug',$item['slug'])->first();
            if($transactions == null){
                TransactionSetting::insert($item);
            }
        }
    }
}
