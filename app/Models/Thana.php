<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_thana');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
