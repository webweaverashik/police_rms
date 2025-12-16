<?php
namespace App\Models\Administrative;

use App\Models\Report\Report;
use App\Models\Administrative\Union;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $fillable = ['name'];

    public function unions()
    {
        return $this->hasMany(Union::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
