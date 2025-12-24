<?php
namespace Database\Factories\Report;

use App\Models\Administrative\Union;
use App\Models\Administrative\Upazila;
use App\Models\Administrative\Zone;
use App\Models\Political\SeatPartyCandidate;
use App\Models\Report\ProgramType;
use App\Models\Report\Report;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tusharkhan\BanglaFaker\BanglaFaker;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $status      = $this->faker->randomElement(['done', 'ongoing', 'upcoming']);
        $banglaFaker = new BanglaFaker();

        $seatParty = SeatPartyCandidate::inRandomOrder()->first();

        $userId = User::whereHas('role', fn($q) => $q->where('name', 'Operator'))
            ->inRandomOrder()
            ->value('id') ?? User::inRandomOrder()->value('id');

        $dateTime = match ($status) {
            'done'     => $this->faker->dateTimeBetween('-15 days', '-1 day'),
            'ongoing'  => $this->faker->dateTimeBetween('-1 hour', '+1 hour'),
            'upcoming' => $this->faker->dateTimeBetween('+1 day', '+15 days'),
        };

        // ✅ Conditional values based on program_status
        $actualAttendeeCount = null;
        $deadInjuredCount    = null;
        $tentativeRisks      = 'no';

        if ($status === 'done') {
            $actualAttendeeCount = (string) $this->faker->numberBetween(50, 3000);

            // 30% chance of having casualties
            $deadInjuredCount = $this->faker->boolean(30)
                ? (string) $this->faker->numberBetween(1, 20)
                : null;

            $tentativeRisks = $this->faker->randomElement(['yes', 'no']);
        }

        return [
            // Political
            'parliament_seat_id'       => $seatParty->parliament_seat_id,
            'political_party_id'       => $seatParty->political_party_id,
            'candidate_name'           => $seatParty->candidate_name,

            // Location
            'upazila_id'               => Upazila::inRandomOrder()->value('id'),
            'zone_id'                  => Zone::inRandomOrder()->value('id'),
            'union_id'                 => Union::inRandomOrder()->value('id'),
            'location_name'            => $banglaFaker->address(),

            // Program details
            'program_type_id'          => ProgramType::inRandomOrder()->value('id'),
            'program_date'             => $dateTime->format('Y-m-d'),
            'program_time'             => $dateTime->format('H:i:s'),

            'program_special_guest'    => $banglaFaker->maleName(),
            'program_chair'            => $banglaFaker->maleName(),
            'tentative_attendee_count' => $this->faker->numberBetween(50, 3000),

            // ✅ Status-related fields
            'program_status'           => $status,
            'tentative_risks'          => $tentativeRisks,
            'actual_attendee_count'    => $actualAttendeeCount,
            'dead_injured_count'       => $deadInjuredCount,

            // Content
            'program_title'            => $banglaFaker->sentences(3, true),
            'program_description'      => $banglaFaker->paragraphs(3, true),

            // Reporting officer
            'created_by'               => $userId,
        ];
    }
}
