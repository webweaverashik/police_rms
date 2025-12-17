<?php
namespace Database\Factories\Political;

use App\Models\Political\ParliamentSeat;
use App\Models\Political\PoliticalParty;
use App\Models\Political\SeatPartyCandidate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tusharkhan\BanglaFaker\BanglaFaker;

class SeatPartyCandidateFactory extends Factory
{
    protected $model = SeatPartyCandidate::class;

    public function definition(): array
    {
        $banglaFaker = new BanglaFaker();

        return [
            'parliament_seat_id' => ParliamentSeat::inRandomOrder()->value('id'),
            'political_party_id' => PoliticalParty::inRandomOrder()->value('id'),
            'candidate_name'     => $banglaFaker->maleName(),
            'election_symbol'    => null, // optional / can be set later
        ];
    }
}
