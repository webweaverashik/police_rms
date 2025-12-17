<?php
namespace Database\Seeders;

use App\Models\Report\Report;
use App\Models\Report\ReportAssignment;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class ReportAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // Safety checks
        $unoIds = User::whereHas('role', fn($q) => $q->where('name', 'Magistrate'))->pluck('id');

        $assignerIds = User::whereHas('role', fn($q) => $q->whereIn('name', ['Admin', 'SuperAdmin']))->pluck('id');

        if ($unoIds->isEmpty() || $assignerIds->isEmpty() || Report::count() === 0) {
            $this->command->warn('ReportAssignmentSeeder skipped: required data missing.');
            return;
        }

        // Assign ~40% of reports to UNO (realistic)
        $reports = Report::inRandomOrder()->limit((int) (Report::count() * 0.4))->get();

        foreach ($reports as $report) {
            // Avoid duplicate assignment
            if ($report->assignment()->exists()) {
                continue;
            }

            ReportAssignment::create([
                'report_id'   => $report->id,
                'user_id'     => $unoIds->random(),
                'assigned_by' => $assignerIds->random(),
            ]);
        }
    }
}
