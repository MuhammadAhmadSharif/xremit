<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\SourceOfFund;
use Illuminate\Database\Seeder;

class SourceOfFundsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $source_of_funds = array(
            array('slug' => 'business','name' => 'Business','status' => '1','created_at' => '2023-08-17 14:14:20','updated_at' => '2023-08-17 14:14:20'),
            array('slug' => 'job','name' => 'Job','status' => '1','created_at' => '2023-08-17 14:14:27','updated_at' => '2023-08-17 14:14:27'),
            array('slug' => 'worker','name' => 'Worker','status' => '1','created_at' => '2023-08-17 14:14:35','updated_at' => '2023-08-17 14:14:35'),
            array('slug' => 'investment','name' => 'Investment','status' => '1','created_at' => '2023-08-17 14:14:44','updated_at' => '2023-08-17 14:14:44'),
            array('slug' => 'salary','name' => 'Salary','status' => '1','created_at' => '2023-08-17 14:14:50','updated_at' => '2023-08-17 14:14:50'),
            array('slug' => 'online','name' => 'Online','status' => '1','created_at' => '2023-08-17 14:14:58','updated_at' => '2023-08-17 14:14:58'),
            array('slug' => 'others','name' => 'Others','status' => '1','created_at' => '2023-08-17 14:15:10','updated_at' => '2023-08-17 14:15:10')
        );
        SourceOfFund::insert($source_of_funds);
    }
}
