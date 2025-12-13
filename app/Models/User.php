<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'bp_number', 'designation_id', 'role_id', 'mobile_no', 'email', 'password', 'is_active'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* --- Relationships --- */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function thanas()
    {
        return $this->belongsToMany(Thana::class, 'user_thana');
    }

    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }

}
