<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Designation;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Designation::insert([
            ['name' => 'আইসিটি অফিসার'],
            ['name' => 'পুলিশ সুপার (এসপি)'],
            ['name' => 'অতিরিক্ত পুলিশ সুপার'],
            ['name' => 'সহকারী পুলিশ সুপার'],
            ['name' => 'উপজেলা নির্বাহী অফিসার'],
            ['name' => 'অফিসার ইনচার্জ'],
            ['name' => 'ইন্সপেক্টর'],
            ['name' => 'সাব-ইন্সপেক্টর'],
            ['name' => 'অ্যাসিস্ট্যান্ট সাব-ইন্সপেক্টর'],
            ['name' => 'কনস্টেবল'],
        ]);

    }
}
