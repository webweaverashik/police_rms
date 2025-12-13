<?php
namespace Database\Factories;

use App\Models\ParliamentSeat;
use App\Models\PoliticalParty;
use App\Models\ProgramType;
use App\Models\Report;
use App\Models\Upazila;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['done', 'ongoing', 'upcoming']);

        // Prefer Operator users
        $userId = User::whereHas('role', fn($q) =>
            $q->where('name', 'Operator')
        )->inRandomOrder()->value('id') ?? User::inRandomOrder()->value('id');

        return [
            'parliament_seat_id'       => ParliamentSeat::inRandomOrder()->value('id'),
            'upazila_id'               => Upazila::inRandomOrder()->value('id'),
            'zone_id'                  => Zone::inRandomOrder()->value('id'),
            'political_party_id'       => PoliticalParty::inRandomOrder()->value('id'),

            'candidate_name'           => $this->faker->name,
            'program_type_id'          => ProgramType::inRandomOrder()->value('id'),

            'program_date_time'        => match ($status) {
                'done'     => $this->faker->dateTimeBetween('-15 days', '-1 day'),
                'ongoing'  => $this->faker->dateTimeBetween('-1 hour', '+1 hour'),
                'upcoming' => $this->faker->dateTimeBetween('+1 day', '+15 days'),
            },

            'program_chair'            => $this->faker->name,
            'tentative_attendee_count' => $this->faker->numberBetween(50, 3000),
            'program_status'           => $status,
            'final_attendee_count'     => $status === 'done'
                ? $this->faker->numberBetween(40, 3500)
                : null,

            'description'              => $this->faker->sentence(15),

            // 👮 Reporting Officer
            'created_by'               => $userId,
        ];
    }
}
