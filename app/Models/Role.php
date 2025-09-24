<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug', 'description'];


public function permissions()
{
    return $this->belongsToMany(
        Permission::class,
        'role_permission',
        'role_id',
        'permission_id'
    );
}

    public function admins()
{
    return $this->hasMany(Admin::class);
}

}
