<?php
namespace App\Models\Political;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Model;
use App\Models\Political\SeatPartyCandidate;

class PoliticalParty extends Model
{
    protected $fillable = ['name', 'party_head'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function seatPartyCandidates()
    {
        return $this->hasMany(SeatPartyCandidate::class);
    }
}
