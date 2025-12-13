<?php
namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Zone::insert([
            ['name' => 'কলাপাড়া জোন'],
            ['name' => 'কলাপাড়া জোন (পায়রা বন্দর)'],
            ['name' => 'গলাচিপা জোন'],
            ['name' => 'দশমিনা জোন'],
            ['name' => 'দুমকি জোন'],
            ['name' => 'পটুয়াখালী সদর জোন'],
            ['name' => 'বাউফল জোন'],
            ['name' => 'মহিপুর জোন'],
            ['name' => 'মির্জাগঞ্জ জোন'],
            ['name' => 'রাঙ্গাবালী জোন'],
        ]);

    }
}
