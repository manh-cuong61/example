<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory;
    //use SoftDeletes;
    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id' , 'role_id' );
    }
}
