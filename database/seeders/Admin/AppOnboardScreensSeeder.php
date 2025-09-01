<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AppOnboardScreens;
use Illuminate\Database\Seeder;

class AppOnboardScreensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_onboard_screens = array(
            array('type' => 'User','title' => '{"language":{"en":{"title":"Welcome to XRemit"},"es":{"title":"Bienvenido a XRemit"},"ar":{"title":"\\u0645\\u0631\\u062d\\u0628\\u0627\\u064b \\u0628\\u0643\\u0645 \\u0641\\u064a XRemit"}}}','sub_title' => '{"language":{"en":{"sub_title":"Smarter way to Send Money anytime,anywhere with best exchange rate"},"es":{"sub_title":"Bienvenido a XRemit"},"ar":{"sub_title":"\\u0645\\u0631\\u062d\\u0628\\u0627\\u064b \\u0628\\u0643\\u0645 \\u0641\\u064a XRemit"}}}','image' => 'agent/onboard1.webp','status' => '1','last_edit_by' => '1','created_at' => '2023-09-03 10:27:23','updated_at' => '2024-09-18 09:29:52'),
            array('type' => 'User','title' => '{"language":{"en":{"title":"Safe & Secure Process"},"es":{"title":"Proceso seguro y protegido"},"ar":{"title":"\\u0639\\u0645\\u0644\\u064a\\u0629 \\u0622\\u0645\\u0646\\u0629 \\u0648\\u0645\\u0623\\u0645\\u0648\\u0646\\u0629"}}}','sub_title' => '{"language":{"en":{"sub_title":"Smarter way to Send Money anytime,anywhere with best exchange rate"},"es":{"sub_title":"La forma m\\u00e1s inteligente de enviar dinero en cualquier momento y lugar con el mejor tipo de cambio"},"ar":{"sub_title":"\\u0637\\u0631\\u064a\\u0642\\u0629 \\u0623\\u0630\\u0643\\u0649 \\u0644\\u0625\\u0631\\u0633\\u0627\\u0644 \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0641\\u064a \\u0623\\u064a \\u0648\\u0642\\u062a \\u0648\\u0641\\u064a \\u0623\\u064a \\u0645\\u0643\\u0627\\u0646 \\u0628\\u0623\\u0641\\u0636\\u0644 \\u0633\\u0639\\u0631 \\u0635\\u0631\\u0641"}}}','image' => 'agent/onboard2.webp','status' => '1','last_edit_by' => '1','created_at' => '2023-09-03 10:29:10','updated_at' => '2024-09-18 09:29:12'),
            array('type' => 'User','title' => '{"language":{"en":{"title":"24\\/7 Customer Support"},"es":{"title":"Atenci\\u00f3n al cliente 24 horas al d\\u00eda, 7 d\\u00edas a la semana"},"ar":{"title":"\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621 \\u0639\\u0644\\u0649 \\u0645\\u062f\\u0627\\u0631 \\u0627\\u0644\\u0633\\u0627\\u0639\\u0629 \\u0637\\u0648\\u0627\\u0644 \\u0623\\u064a\\u0627\\u0645 \\u0627\\u0644\\u0623\\u0633\\u0628\\u0648\\u0639"}}}','sub_title' => '{"language":{"en":{"sub_title":"Smarter way to Send Money anytime,anywhere with best exchange rate"},"es":{"sub_title":"La forma m\\u00e1s inteligente de enviar dinero en cualquier momento y lugar con el mejor tipo de cambio"},"ar":{"sub_title":"\\u0637\\u0631\\u064a\\u0642\\u0629 \\u0623\\u0630\\u0643\\u0649 \\u0644\\u0625\\u0631\\u0633\\u0627\\u0644 \\u0627\\u0644\\u0623\\u0645\\u0648\\u0627\\u0644 \\u0641\\u064a \\u0623\\u064a \\u0648\\u0642\\u062a \\u0648\\u0641\\u064a \\u0623\\u064a \\u0645\\u0643\\u0627\\u0646 \\u0628\\u0623\\u0641\\u0636\\u0644 \\u0633\\u0639\\u0631 \\u0635\\u0631\\u0641"}}}','image' => 'agent/onboard3.webp','status' => '1','last_edit_by' => '1','created_at' => '2023-09-03 10:30:10','updated_at' => '2024-09-18 09:28:40'),
        );

        AppOnboardScreens::insert($app_onboard_screens);
    }
}
