<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\SendingPurpose;
use Illuminate\Database\Seeder;

class SendingPurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sending_purposes = array(
            array('name' => 'Family','slug' => 'family','status' => '1','created_at' => '2023-08-07 09:57:01','updated_at' => '2023-08-17 14:07:41'),
            array('name' => 'Friend','slug' => 'friend','status' => '1','created_at' => '2023-08-07 09:56:52','updated_at' => '2023-08-17 14:08:01'),
            array('name' => 'Relative','slug' => 'relative','status' => '1','created_at' => '2023-08-07 09:56:25','updated_at' => '2023-08-17 14:08:10'),
            array('name' => 'Cousin','slug' => 'cousin','status' => '1','created_at' => '2023-08-17 14:08:18','updated_at' => '2023-08-17 14:08:18'),
            array('name' => 'Brother','slug' => 'brother','status' => '1','created_at' => '2023-08-17 14:08:25','updated_at' => '2023-08-17 14:08:25'),
            array('name' => 'Sister','slug' => 'sister','status' => '1','created_at' => '2023-08-17 14:08:34','updated_at' => '2023-08-17 14:08:34'),
            array('name' => 'Father','slug' => 'father','status' => '1','created_at' => '2023-08-17 14:08:41','updated_at' => '2023-08-17 14:08:41'),
            array('name' => 'Mother','slug' => 'mother','status' => '1','created_at' => '2023-08-17 14:08:54','updated_at' => '2023-08-17 14:08:54'),
            array('name' => 'Colleague','slug' => 'colleague','status' => '1','created_at' => '2023-08-17 14:09:18','updated_at' => '2023-08-17 14:09:18'),
            array('name' => 'Boy Friend','slug' => 'boy-friend','status' => '1','created_at' => '2023-08-17 14:09:31','updated_at' => '2023-08-17 14:09:31'),
            array('name' => 'Girl Friend','slug' => 'girl-friend','status' => '1','created_at' => '2023-08-17 14:09:49','updated_at' => '2023-08-17 14:09:49'),
            array('name' => 'Others','slug' => 'others','status' => '1','created_at' => '2023-08-17 14:09:55','updated_at' => '2023-08-17 14:09:55')
          );
        SendingPurpose::insert($sending_purposes);
    }
}
