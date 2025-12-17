<?php
namespace Database\Seeders;

use App\Models\Political\ParliamentSeat;
use App\Models\Political\PoliticalParty;
use App\Models\Political\SeatPartyCandidate;
use Illuminate\Database\Seeder;
use Tusharkhan\BanglaFaker\BanglaFaker;

class SeatPartyCandidateSeeder extends Seeder
{
    public function run(): void
    {
        if (ParliamentSeat::count() === 0 || PoliticalParty::count() === 0) {
            $this->command->warn('SeatPartyCandidateSeeder skipped: ParliamentSeat or PoliticalParty missing.');
            return;
        }

        $banglaFaker = new BanglaFaker();
        $parties     = PoliticalParty::all();

        foreach (ParliamentSeat::all() as $seat) {
            /*
            |----------------------------------------------------------
            | Each seat realistically has 3–6 contesting parties
            |----------------------------------------------------------
            */
            $contestParties = $parties->random(rand(3, min(6, $parties->count())));

            foreach ($contestParties as $party) {
                SeatPartyCandidate::updateOrCreate(
                    [
                        'parliament_seat_id' => $seat->id,
                        'political_party_id' => $party->id,
                    ],
                    [
                        'candidate_name'  => $banglaFaker->maleName(),
                        'election_symbol' => null,
                    ],
                );
            }
        }
    }
}
