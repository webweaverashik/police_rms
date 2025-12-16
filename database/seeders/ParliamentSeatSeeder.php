<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Political\ParliamentSeat;

class ParliamentSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParliamentSeat::insert([
            ['name' => 'পটুয়াখালী-১', 'description' => '১১১ পটুয়াখালী-১ আসনটি মির্জাগঞ্জ উপজেলা, দুমকি উপজেলা এবং পটুয়াখালী সদর উপজেলা নিয়ে গঠিত'],
            ['name' => 'পটুয়াখালী-২', 'description' => '১১২ পটুয়াখালী-২ আসনটি বাউফল উপজেলা ও পটুয়াখালী সদর উপজেলার লোহালিয়া ও কমলাপুর ইউনিয়ন নিয়ে গঠিত'],
            ['name' => 'পটুয়াখালী-৩', 'description' => '১১৩ পটুয়াখালী-৩ আসনটি দশমিনা উপজেলা এবং গলাচিপা উপজেলা  নিয়ে গঠিত'],
            ['name' => 'পটুয়াখালী-৪', 'description' => '১১৪ পটুয়াখালী-৪ আসনটি কলাপাড়া উপজেলা এবং রাঙ্গাবালী উপজেলা  নিয়ে গঠিত'],
        ]);
    }
}
