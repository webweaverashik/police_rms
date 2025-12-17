<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrative\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Zone::insert([
            ['name' => 'কলাপাড়া'],
            ['name' => 'গলাচিপা'],
            ['name' => 'দশমিনা জোন'],
            ['name' => 'দুমকি'],
            ['name' => 'পটুয়াখালী সদর'],
            ['name' => 'বাউফল'],
            ['name' => 'মহিপুর'],
            ['name' => 'মির্জাগঞ্জ'],
            ['name' => 'রাঙ্গাবালী'],
        ]);

    }
}
