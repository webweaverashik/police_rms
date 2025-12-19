<?php
namespace App\Models\Administrative;

use App\Models\Report\Report;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['name', 'upazila_id'];

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_zone');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
