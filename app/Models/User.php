<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Permission;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }

    public function hasPermission(Permission $permission)
    {

        $check = false;
        foreach ($this->roles as $role) {
            $check = !!$role->permissions->contains($permission);
            if ($check == true) {
                break;
            }
        }
        return $check;
    }

    public function scopeRole($query, $role)
    {
        return $query->whereHas('roles',  function (Builder $query) use ($role) {
            $query->where('role_id', $role);
        })->with('roles');
    }

    public function scopeSearch($query, $search)
    {
        return $query ->where(function (Builder $query) use ($search) {
            return $query->where('name', 'like',  "%$search%")
                         ->orWhere('email', 'like',  "%$search%");
        });
    }

    public function scopePermission($query, $permission)
    {
        return $query->whereHas('roles',  function (Builder $query) use ($permission) {
            $query->whereHas('permissions',  function (Builder $query) use ($permission) {
                $query->where('permission_id', $permission);
            });
        });
    }

    
}
