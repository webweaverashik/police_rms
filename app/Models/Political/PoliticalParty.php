<?php
namespace App\Models\Political;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Model;

class PoliticalParty extends Model
{
    protected $fillable = ['name', 'party_head', 'local_office_address'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
