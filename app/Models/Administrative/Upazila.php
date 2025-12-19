<?php
namespace App\Models\Administrative;

use App\Models\Administrative\Union;
use App\Models\Political\ParliamentSeat;
use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $fillable = ['name', 'parliament_seat_id'];

    public function parliament_seat()
    {
        return $this->belongsTo(ParliamentSeat::class);
    }
    public function unions()
    {
        return $this->hasMany(Union::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
