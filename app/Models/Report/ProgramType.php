<?php
namespace App\Models\Report;

use App\Models\Report\Report;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class ProgramType extends Model
{
    protected $fillable = ['name', 'description', 'created_by'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }
}
