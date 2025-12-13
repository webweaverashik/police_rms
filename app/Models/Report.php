<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['parliament_seat_id', 'thana_id', 'political_party_id', 'candidate_name', 'program_type_id', 'program_date_time', 'program_chair', 'tentative_attendee_count', 'program_status', 'final_attendee_count', 'description'];

    public function thana()
    {
        return $this->belongsTo(Thana::class);
    }
    public function party()
    {
        return $this->belongsTo(PoliticalParty::class, 'political_party_id');
    }
    public function seat()
    {
        return $this->belongsTo(ParliamentSeat::class);
    }
    public function programType()
    {
        return $this->belongsTo(ProgramType::class);
    }
}
