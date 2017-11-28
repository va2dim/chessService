<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game
  extends Model
{
    //public $winner = 1;
    protected $fillable = ['startDatetime', 'endDatetime', 'winner_id'];
    //protected $hidden = ['winner_id'];
    //protected $appends = ['player1', 'player2', 'winner', 'log'];
    //protected $appends = ['players'];
    //protected $appends = ['players', 'winner']; //вместо winner лучше result, т.к. в некоторых играх (шахматы) может быть ничья

    public $timestamps = false;


    public function players()
    {

        return $this->belongsToMany(Player::class);
    }


    public function getLogAttribute()
    {
        $url = url('gamesLogs/'.$this->id.'.pgn');

        return $url;
    }

/*
    public function getPlayersAttribute()
    {
        $players = $this->players()->get();

        return $players;
    }
*/

    public function getPlayer1Attribute()
    {
        $players = $this->players;
        $player_name = $players[0]->name;

        return $player_name;
    }

    public function getPlayer2Attribute()
    {
        $players = $this->players;
        $player_name = $players[1]->name;

        return $player_name;
    }

    public function getWinnerAttribute($value)
    {
        $winner = Player::findOrFail($this->winner_id)->name;

        return $winner;
    }

    public function winner()
    {
        $winner = Player::findOrFail($this->winner_id)->name;
        $this->belongsTo(Player::class, $this->winner_id);

        return $winner;
    }

    /**
     * Calculate ratings elo for both players,
     * Ea = 1 / (1 + 10^((Rb-Ra)/400))
     * Ra’ = Ra + K * (Sa — Ea)
     *
     * @param $this (Game $game)
     * @param $this->players (2 Player)
     *
     * TODO: RatingDiff400 rule
     * @return array NewRatings
     */
    public function calculateRating()
    {
        /* // Test data
        $result = 0.5;
        $players[0] = ['name' => 'Karpov', 'rating' => 2300]; //newRating
        $players[1] = ['name' => 'Kasparov', 'rating' => 1400];
        */


        $players = $this->players;


        if ($players[0]->id == $this->winner_id) {
            $result = 1;
        } elseif ($players[1]->id == $this->winner_id) {
            $result = 0;
        } else {
            $result = 0.5; // если не указан победитель
        }



        /*
              $players = [];
              $players[0] = new \ArrayObject();
              $players[0]->setFlags(\ArrayObject::STD_PROP_LIST | \ArrayObject::ARRAY_AS_PROPS);
              $players[1] = new \ArrayObject();
              $players[1]->setFlags(\ArrayObject::STD_PROP_LIST | \ArrayObject::ARRAY_AS_PROPS);
      */




        if (1 == $result) {
            $players[0]['result'] = 1;
            $players[1]['result'] = 0;
        } elseif (0 == $result) {
            $players[0]['result'] = 0;
            $players[1]['result'] = 1;
        } elseif (0.5 == $result) {
            $players[0]['result'] = 0.5;
            $players[1]['result'] = 0.5;
        }

        // вычисление ожидаемого рез-та
        $expextedResult = function ($rating1, $rating2) {
            $n = ($rating2 - $rating1) / 400;
            return round(1 / (1 + 10 ** ($n)), 2);
        };

        $players[0]['expextedResult'] = $expextedResult($players[0]['rating'], $players[1]['rating']);
        $players[1]['expextedResult'] = $expextedResult($players[1]['rating'], $players[0]['rating']);


        // TODO: +учет кол-ва матчей (30) (и возраст - до 18)
        $K = function ($rating) {
            if (1400 == $rating) { //К для новых игроков
                return 40;
            } elseif (2400 <= $rating) { //К для сильных игроков
                return 10;
            } elseif (2400 > $rating) {
                return 20;
            }
        };

        $newRating = function($rating, $K, $result, $expextedResult) {
            $nominativeChangeRating = $K * ($result - $expextedResult);
            $newRating = $rating + $nominativeChangeRating;
            return round($newRating);
        };

        //$players[0]['newRating'] = $newRating($players[0]['rating'], $K($players[0]['rating']), $players[0]['result'], $players[0]['expextedResult']);
        //$players[1]['newRating'] = $newRating($players[1]['rating'], $K($players[1]['rating']), $players[1]['result'], $players[1]['expextedResult']);

        $players_newRating[] = $newRating($players[0]['rating'], $K($players[0]['rating']), $players[0]['result'], $players[0]['expextedResult']);
        $players_newRating[] = $newRating($players[1]['rating'], $K($players[1]['rating']), $players[1]['result'], $players[1]['expextedResult']);

        unset ($players[0]['result'], $players[0]['expextedResult'], $players[1]['result'], $players[1]['expextedResult']);

        //return [$players[0]['newRating'],$players[1]['newRating']];
        return $players_newRating;


        /*
        // K-range with rule 400 in it
        switch ($ratingDiff){
            case > 0:
                $players[0]->waitnigResult = 0.5;
                $players[1]->waitnigResult = 0.5;
                break;
            case > 100:
                $players[0]->waitnigResult = 0.64;
                $players[1]->waitnigResult = 0.36;
                break;

            case > 250:
                $players[0]->waitnigResult = 0.81;
                $players[1]->waitnigResult = 0.19;
                break;

            case > 400:
                $players[0]->waitnigResult = 0.92;
                $players[1]->waitnigResult = 0.08;
                break;
        }
        */


    }

}
