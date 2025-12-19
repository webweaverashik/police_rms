<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrative\Zone;
use App\Models\Administrative\Upazila;

class ZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            'পটুয়াখালী সদর' => ['পটুয়াখালী সদর থানা'],
            'বাউফল'         => ['বাউফল থানা'],
            'দশমিনা'        => ['দশমিনা থানা'],
            'গলাচিপা'       => ['গলাচিপা থানা'],
            'কলাপাড়া'       => ['কলাপাড়া থানা', 'মহিপুর থানা'],
            'মির্জাগঞ্জ'    => ['মির্জাগঞ্জ থানা'],
            'দুমকি'         => ['দুমকি থানা'],
            'রাঙ্গাবালী'    => ['রাঙ্গাবালী থানা'],
        ];

        foreach ($zones as $upazilaName => $thanas) {
            $upazila = Upazila::where('name', $upazilaName)->first();

            if (! $upazila) {
                $this->command->warn("Upazila not found: {$upazilaName}");
                continue;
            }

            foreach ($thanas as $thana) {
                Zone::updateOrCreate(
                    [
                        'name'       => $thana,
                        'upazila_id' => $upazila->id,
                    ],
                    [
                        'name'       => $thana,
                        'upazila_id' => $upazila->id,
                    ],
                );
            }
        }
    }
}
