<?php
namespace App\Models\Report;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Model;

class ProgramType extends Model
{
    protected $fillable = ['name', 'description'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
