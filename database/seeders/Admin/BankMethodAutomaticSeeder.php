<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\BankMethodAutomatic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankMethodAutomaticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank_method_automatic = array(
            array('admin_id' => '1','slug' => 'flutterwave','name' => 'Flutterwave','image' => 'seeder/virtual-card.png','details' => 'This card is property of XRemit, Wonderland. Misuse is criminal offence. If found, please return to XRemit or to the nearest bank.','config' => '{"flutterwave_secret_key":"FLWSECK_TEST-SANDBOXDEMOKEY-X","flutterwave_secret_hash":"AYxcfvgbhnj@34","flutterwave_url":"https:\/\/api.flutterwave.com\/v3","name":"flutterwave"}','status' => '1','created_at' => now(),'updated_at' => now())
        );
        BankMethodAutomatic::upsert($bank_method_automatic,['slug'],['details']);
    }
}
