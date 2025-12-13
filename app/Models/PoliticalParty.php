<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoliticalParty extends Model
{
    protected $fillable = ['name', 'party_head', 'local_office_address'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
