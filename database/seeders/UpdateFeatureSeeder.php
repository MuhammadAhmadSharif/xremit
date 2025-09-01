<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use App\Models\Admin\AppSettings;
use App\Models\Admin\BasicSettings;

class UpdateFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(BasicSettings::first()) {
            BasicSettings::first()->update([
                'web_version'       => "2.6.0",
            ]);
        }
        if(AppSettings::first()){
            AppSettings::first()->update(['version' => '2.6.0']);
        }

        try{
            update_project_localization_data();
        }catch(Exception $e) {
            // handle error
        }
    }
}
