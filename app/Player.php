<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public $timestamps = false;

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
