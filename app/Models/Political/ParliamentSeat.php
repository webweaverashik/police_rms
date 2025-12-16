<?php
namespace App\Models\Political;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Model;

class ParliamentSeat extends Model
{
    protected $fillable = ['name', 'description'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
