<?php
namespace Database\Seeders;

use App\Models\ParliamentSeat;
use Illuminate\Database\Seeder;

class ParliamentSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParliamentSeat::insert([
            ['name' => '১১১ পটুয়াখালী-১', 'description' => 'মির্জাগঞ্জ উপজেলা, দুমকি উপজেলা এবং পটুয়াখালী সদর উপজেলা'],
            ['name' => '১১২ পটুয়াখালী-২', 'description' => 'বাউফল উপজেলা ও পটুয়াখালী সদর উপজেলার লোহালিয়া ও কমলাপুর ইউনিয়ন'],
            ['name' => '১১৩ পটুয়াখালী-৩', 'description' => 'দশমিনা উপজেলা এবং গলাচিপা উপজেলা'],
            ['name' => '১১৪ পটুয়াখালী-৪', 'description' => 'কলাপাড়া উপজেলা এবং রাঙ্গাবালী উপজেলা'],
        ]);
    }
}
