<?php
namespace App\Models\Report;

use App\Models\Administrative\Union;
use App\Models\Administrative\Upazila;
use App\Models\Administrative\Zone;
use App\Models\Political\ParliamentSeat;
use App\Models\Political\PoliticalParty;
use App\Models\Political\SeatPartyCandidate;
use App\Models\Report\ProgramType;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Report
 *
 * Represents a political program report recorded by police authorities.
 * Each report contains information about a political program, its location,
 * organizers, participants, and current status.
 */
class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * These fields are filled during report creation and update.
     */
    use HasFactory, SoftDeletes;

    protected $fillable = ['upazila_id', 'zone_id', 'union_id', 'location_name', 'parliament_seat_id', 'political_party_id', 'candidate_name', 'program_type_id', 'program_date', 'program_time', 'program_special_guest', 'program_chair', 'tentative_attendee_count', 'program_status', 'program_title', 'program_description', 'created_by', 'deleted_by'];

    protected $casts = [
        'program_date' => 'date',
        'program_time' => 'datetime:H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the thana (police station) where the program was held.
     */
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    /**
     * Get the zone (police station) where the program was held.
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Get the union where the program was held.
     */
    public function union()
    {
        return $this->belongsTo(Union::class);
    }

    /**
     * Get the political party associated with the program.
     */
    public function politicalParty()
    {
        return $this->belongsTo(PoliticalParty::class);
    }

    /**
     * Get the parliamentary seat under which the program took place.
     */
    public function parliamentSeat()
    {
        return $this->belongsTo(ParliamentSeat::class);
    }

    /**
     * Get the type of program (e.g., rally, meeting, human chain).
     */
    public function programType()
    {
        return $this->belongsTo(ProgramType::class);
    }

    /**
     * Reports created by this user.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    /**
     * Reports deleted by this user.
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by')->withTrashed();
    }

    // Report assignment to UNO
    public function assignments()
    {
        return $this->hasMany(ReportAssignment::class);
    }
}
