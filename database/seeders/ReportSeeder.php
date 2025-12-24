<?php
namespace Database\Seeders;

use App\Models\Administrative\Union;
use App\Models\Administrative\Upazila;
use App\Models\Administrative\Zone;
use App\Models\Political\ParliamentSeat;
use App\Models\Political\PoliticalParty;
use App\Models\Report\ProgramType;
use App\Models\Report\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Safety check: required master data must exist
        if (ParliamentSeat::count() === 0 || Upazila::count() === 0 || Zone::count() === 0 || Union::count() === 0 || PoliticalParty::count() === 0 || ProgramType::count() === 0) {
            $this->command->warn('ReportSeeder skipped: required master data missing.');
            return;
        }

        // Seed realistic reports
        Report::factory()->count(500)->create();
    }
}
