<?php
namespace App\Models\User;

use App\Models\Administrative\Zone;
use App\Models\Report\Report;
use App\Models\User\Designation;
use App\Models\User\LoginActivity;
use App\Models\User\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'bp_number',
        'designation_id',
        'role_id',
        'mobile_no',
        'email',
        'password',
        'is_active',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Attributes
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'is_active'         => 'boolean',
        'email_verified_at' => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /** User → Role */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /** User → Designation */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /** User → Multiple Zones */
    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'user_zone');
    }

    /** User → Login Activities */
    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }

    /**
     * Reports created by this user.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /** Check specific role */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /** Super Admin shortcut */
    public function isAdmin(): bool
    {
        return $this->hasRole('Administrator');
    }

    /** Check active status */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

}
