<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\CountrySection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country_sections = array(
            array('key' => 'country','value' => '{"language":{"en":{"title":"Monitoring Real-time Foreign Exchange (FX) Rates for Remittance Sending Worldwide."},"es":{"title":"Monitoreo de tasas de cambio de divisas (FX) en tiempo real para el env\\u00edo de remesas en todo el mundo."},"ar":{"title":"\\u0645\\u0631\\u0627\\u0642\\u0628\\u0629 \\u0623\\u0633\\u0639\\u0627\\u0631 \\u0635\\u0631\\u0641 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u062a \\u0627\\u0644\\u0623\\u062c\\u0646\\u0628\\u064a\\u0629 (FX) \\u0641\\u064a \\u0627\\u0644\\u0648\\u0642\\u062a \\u0627\\u0644\\u062d\\u0642\\u064a\\u0642\\u064a \\u0644\\u0625\\u0631\\u0633\\u0627\\u0644 \\u0627\\u0644\\u062a\\u062d\\u0648\\u064a\\u0644\\u0627\\u062a \\u0641\\u064a \\u062c\\u0645\\u064a\\u0639 \\u0623\\u0646\\u062d\\u0627\\u0621 \\u0627\\u0644\\u0639\\u0627\\u0644\\u0645."}}}','status' => '1','created_at' => '2023-11-08 08:16:38','updated_at' => '2023-11-08 08:17:41')
        );
        CountrySection::insert($country_sections,['key'],['value','status']);
    }
}
