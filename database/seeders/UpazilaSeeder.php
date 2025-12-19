<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrative\Upazila;
use App\Models\Political\ParliamentSeat;

class UpazilaSeeder extends Seeder
{
    public function run(): void
    {
        $seat111 = ParliamentSeat::where('name', 'পটুয়াখালী-১')->first();
        $seat112 = ParliamentSeat::where('name', 'পটুয়াখালী-২')->first();
        $seat113 = ParliamentSeat::where('name', 'পটুয়াখালী-৩')->first();
        $seat114 = ParliamentSeat::where('name', 'পটুয়াখালী-৪')->first();

        if (! $seat111 || ! $seat112 || ! $seat113 || ! $seat114) {
            $this->command->warn('ParliamentSeat missing. Please seed ParliamentSeat first.');
            return;
        }

        $upazilas = [
            // Seat 111
            ['name' => 'পটুয়াখালী সদর', 'parliament_seat_id' => $seat111->id],
            ['name' => 'দুমকি', 'parliament_seat_id' => $seat111->id],
            ['name' => 'মির্জাগঞ্জ', 'parliament_seat_id' => $seat111->id],

            // Seat 112
            ['name' => 'বাউফল', 'parliament_seat_id' => $seat112->id],

            // Seat 113
            ['name' => 'গলাচিপা', 'parliament_seat_id' => $seat113->id],
            ['name' => 'দশমিনা', 'parliament_seat_id' => $seat113->id],

            // Seat 114
            ['name' => 'কলাপাড়া', 'parliament_seat_id' => $seat114->id],
            ['name' => 'রাঙ্গাবালী', 'parliament_seat_id' => $seat114->id],
        ];

        foreach ($upazilas as $upazila) {
            Upazila::updateOrCreate(
                ['name' => $upazila['name']],
                $upazila
            );
        }
    }
}
