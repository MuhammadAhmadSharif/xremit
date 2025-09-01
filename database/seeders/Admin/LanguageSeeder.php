<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages      = Language::get()->pluck("code")->toArray();
        if(count($languages) > 0) {
            $files          = File::files(base_path('lang'));
            $json_files      = array_filter($files, function ($file) {
                return $file->getExtension() === 'json' && $file->getBasename() != "predefined_keys.json"; 
            });
            $file_names      = array_map(function ($file) {
                return pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }, $json_files);
            $diff_items = array_diff($file_names, $languages);
            foreach($diff_items as $item){
                $file_link = base_path('lang/' . $item . ".json");
                if(file_exists($file_link)) {
                    File::delete($file_link);
                }
            }
        }
        
        $data = [
            [
                'name'              => "English",
                'code'              => "en",
                'status'            => 1,
                'last_edit_by'      => 1,
                'dir'               =>'ltr'
            ],
            [
                'name'              => "Spanish",
                'code'              => "es",
                'status'            => 0,
                'last_edit_by'      => 1,
                'dir'               =>'ltr'
            ],
            [
                'name'              => "Arabic",
                'code'              => "ar",
                'status'            => 0,
                'last_edit_by'      => 1,
                'dir'               =>'rtl'
            ],
            [
                'name'              => "French",
                'code'              => "fr",
                'status'            => 0,
                'last_edit_by'      => 1,
                'dir'               =>'ltr'
            ],
            [
                'name'              => "Hindi",
                'code'              => "hi",
                'status'            => 0,
                'last_edit_by'      => 1,
                'dir'               =>'ltr'
            ]
        ];
        Language::upsert($data,['code'],['name','code','status','last_edit_by','created_at','dir']);
    }
}
