<?php

namespace Database\Seeders\Update;

use Illuminate\Database\Seeder;
use App\Models\Admin\BasicSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BasicSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'web_version'       => "2.6.0"
        ];
        $basicSettings = BasicSettings::first();
        if($basicSettings->site_name = "XRemit"){
            $basicSettings->update([
                'site_name'     => "XRemit",
                'web_version'   => $data['web_version']
            ]);
        }else{
            $basicSettings->update($data);
        }
    }
}
