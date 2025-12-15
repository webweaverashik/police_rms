<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    protected $fillable = [
        'name', 'upazila_id',
    ];

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
