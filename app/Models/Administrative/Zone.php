<?php
namespace App\Models\Administrative;

use App\Models\User\User;
use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_zone');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
