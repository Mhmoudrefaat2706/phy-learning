<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_permissions', 'permission_id', 'admin_id');
    }

public function roles()
{
    return $this->belongsToMany(
        Role::class,
        'role_permission',
        'permission_id',
        'role_id'
    );
}

}
