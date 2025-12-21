<?php

namespace App\Models\Political;

use Illuminate\Database\Eloquent\Model;

class SeatPartyCandidate extends Model
{
    protected $fillable = [
        'candidate_name',
        'election_symbol',
        'political_party_id',
        'parliament_seat_id'
    ];

    public function seat()
    {
        return $this->belongsTo(ParliamentSeat::class, 'parliament_seat_id');
    }

    public function party()
    {
        return $this->belongsTo(PoliticalParty::class, 'political_party_id');
    }
}

