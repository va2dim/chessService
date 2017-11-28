<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player
  extends Model
{
    protected $fillable = ['id', 'name', 'rating'];
    public $timestamps = false;


    public function games()
    {

        return $this->belongsToMany(Game::class);
    }
}
