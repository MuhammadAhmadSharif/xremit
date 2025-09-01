<?php

namespace Database\Seeders\User;


use App\Models\Recipient;
use Illuminate\Database\Seeder;

class BeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipients = array(
            array('user_id' => '1','first_name' => 'Phelan','middle_name' => 'Quincy Savage','last_name' => 'Parsons','email' => 'gahepuqaq@mailinator.com','country' => 'Nigeria','city' => 'Adipisicing dicta du','state' => 'Autem minima expedit','zip_code' => '56279','phone' => '18','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'FBNMobile','iban_number' => '74100225852','address' => 'Quia exercitation qu','document_type' => NULL,'front_image' => NULL,'back_image' => NULL,'created_at' => '2024-01-26 04:08:54','updated_at' => '2024-01-26 04:08:54'),
            array('user_id' => '1','first_name' => 'Yoko','middle_name' => 'Erica Dickerson','last_name' => 'Wolf','email' => 'moboz@mailinator.com','country' => 'Kenya','city' => 'Aperiam in illum au','state' => 'Consequatur in unde','zip_code' => '94284','phone' => '21','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'Kenya Commercial Bank Limited','iban_number' => '151545641215','address' => 'Consequat Voluptate','document_type' => NULL,'front_image' => NULL,'back_image' => NULL,'created_at' => '2024-01-26 04:10:22','updated_at' => '2024-01-26 04:10:22'),
            array('user_id' => '1','first_name' => 'Thomas','middle_name' => 'Dai Carpenter','last_name' => 'Meyers','email' => 'kulutajy@mailinator.com','country' => 'Senegal','city' => 'Vel incididunt solut','state' => 'Voluptate labore aut','zip_code' => '44388','phone' => '66','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'Bank Of Africa','iban_number' => '83356464654','address' => 'A quaerat nisi fugit','document_type' => NULL,'front_image' => NULL,'back_image' => NULL,'created_at' => '2024-01-26 04:10:47','updated_at' => '2024-01-26 04:11:00'),
            array('user_id' => '1','first_name' => 'Randall','middle_name' => 'Samson Alston','last_name' => 'Mendez','email' => 'lana@mailinator.com','country' => 'Senegal','city' => 'Cupiditate numquam v','state' => 'Voluptas nulla commo','zip_code' => '39837','phone' => '41','method' => 'Bank Transfer','mobile_name' => NULL,'account_number' => NULL,'bank_name' => 'BICIS','iban_number' => '605878975455','address' => 'Dolores deserunt vol','document_type' => NULL,'front_image' => NULL,'back_image' => NULL,'created_at' => '2024-01-26 04:11:30','updated_at' => '2024-01-26 04:11:30')
        );
        Recipient::insert($recipients);
    }
}
