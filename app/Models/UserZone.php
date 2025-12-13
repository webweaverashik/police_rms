<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserZone extends Model
{
    protected $fillable = [
        'user_id',
        'zone_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
