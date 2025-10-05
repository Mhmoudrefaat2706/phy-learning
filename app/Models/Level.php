<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
