<?php
namespace Database\Factories\Report;

use App\Models\User\User;
use App\Models\Report\Report;
use App\Models\Report\ProgramType;
use App\Models\Administrative\Zone;
use App\Models\Administrative\Union;
use App\Models\Administrative\Upazila;
use Tusharkhan\BanglaFaker\BanglaFaker;
use App\Models\Political\ParliamentSeat;
use App\Models\Political\PoliticalParty;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['done', 'ongoing', 'upcoming']);

        $banglaFaker = new BanglaFaker();

        // Prefer Operator users
        $userId = User::whereHas('role', fn($q) => $q->where('name', 'Operator'))->inRandomOrder()->value('id') ?? User::inRandomOrder()->value('id');

        return [
            'parliament_seat_id'       => ParliamentSeat::inRandomOrder()->value('id'),
            'upazila_id'               => Upazila::inRandomOrder()->value('id'),
            'zone_id'                  => Zone::inRandomOrder()->value('id'),
            'union_id'                 => Union::inRandomOrder()->value('id'),
            'political_party_id'       => PoliticalParty::inRandomOrder()->value('id'),

            'candidate_name'           => $banglaFaker->maleName(),
            'program_type_id'          => ProgramType::inRandomOrder()->value('id'),

            'location_name'            => $banglaFaker->address(),
            'program_date_time'        => match ($status) {
                'done'     => $this->faker->dateTimeBetween('-15 days', '-1 day'),
                'ongoing'  => $this->faker->dateTimeBetween('-1 hour', '+1 hour'),
                'upcoming' => $this->faker->dateTimeBetween('+1 day', '+15 days'),
            },

            'program_special_guest'    => $banglaFaker->maleName(),
            'program_chair'            => $banglaFaker->maleName(),
            'tentative_attendee_count' => $this->faker->numberBetween(50, 3000),
            'program_status'           => $status,
            'final_attendee_count'     => $status === 'done' ? $this->faker->numberBetween(40, 3500) : null,

            'program_title'            => $banglaFaker->sentences($nb = 3, $asText = true),
            'program_description'      => $banglaFaker->paragraphs($nb = 3, $asText = true),

            // 👮 Reporting Officer
            'created_by'               => $userId,
        ];
    }
}
