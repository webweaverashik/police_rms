<?php
namespace App\Models\Political;

use App\Models\Political\SeatPartyCandidate;
use App\Models\Report\Report;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class PoliticalParty extends Model
{
    protected $fillable = ['name', 'party_head', 'created_by'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function seatPartyCandidates()
    {
        return $this->hasMany(SeatPartyCandidate::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
