<?php
namespace Database\Factories\Report;

use App\Models\Report\Report;
use App\Models\Report\ReportAssignment;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportAssignmentFactory extends Factory
{
    protected $model = ReportAssignment::class;

    public function definition(): array
    {
        // Pick a Magistrate (UNO)
        $unoId = User::whereHas('role', fn($q) => $q->where('name', 'Magistrate'))->inRandomOrder()->value('id');

        // Pick Admin or SuperAdmin as assigner
        $assignedById = User::whereHas('role', fn($q) => $q->whereIn('name', ['Admin', 'SuperAdmin']))->inRandomOrder()->value('id');

        return [
            'report_id'   => Report::inRandomOrder()->value('id'),
            'user_id'     => $unoId,
            'assigned_by' => $assignedById,
        ];
    }
}
