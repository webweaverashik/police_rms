<?php
namespace Database\Seeders;

use App\Models\Upazila;
use Illuminate\Database\Seeder;

class UpazilaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Upazila::insert([
            ['name' => 'পটুয়াখালী সদর'],
            ['name' => 'কলাপাড়া'],
            ['name' => 'গলাচিপা'],
            ['name' => 'দশমিনা'],
            ['name' => 'দুমকী'],
            ['name' => 'বাউফল'],
            ['name' => 'মির্জাগঞ্জ'],
            ['name' => 'রাঙ্গাবালি'],
        ]);
    }
}
