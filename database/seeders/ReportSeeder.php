<?php
namespace Database\Seeders;

use App\Models\ParliamentSeat;
use App\Models\PoliticalParty;
use App\Models\ProgramType;
use App\Models\Report;
use App\Models\Upazila;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Safety check: required master data must exist
        if (
            ParliamentSeat::count() === 0 ||
            Upazila::count() === 0 ||
            Zone::count() === 0 ||
            PoliticalParty::count() === 0 ||
            ProgramType::count() === 0
        ) {
            $this->command->warn(
                'ReportSeeder skipped: required master data missing.'
            );
            return;
        }

        // Seed realistic reports
        Report::factory()
            ->count(60)
            ->create();
    }
}
