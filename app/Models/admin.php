<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name','email','phone','password','role_id','status','last_login_at',
    ];

    protected $hidden = [
        'password','remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }



    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }


}
