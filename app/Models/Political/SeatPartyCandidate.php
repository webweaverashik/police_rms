<?php

namespace App\Models\Political;

use Illuminate\Database\Eloquent\Model;

class SeatPartyCandidate extends Model
{
    protected $fillable = [
        'parliament_seat_id',
        'political_party_id',
        'candidate_name',
        'candidate_age',
        'candidate_address',
        'political_background',
        'election_symbol'
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

