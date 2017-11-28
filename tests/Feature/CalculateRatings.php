<?php

namespace Tests\Feature;

use App\Game;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalculateRatings
  extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function CalculateRatings()
    {
        $game = new Game;
        $game[0] = ['id' => 1, 'winner_id' => 1];

        $players[0] = ['id' => 1, 'name' => 'Karpov', 'rating' => 2700];
        $players[1] = ['id' => 2, 'name' => 'Kasparov', 'rating' => 2450];
        // result 1:0, newRating = [2702 (+2), 2448 (-2)]
        // result 0:1, newRating = [2692 (-8), 2458 (+8)]

        $players[0] = ['id' => 1, 'name' => 'Gelfand', 'rating' => 2400];
        $players[1] = ['id' => 2, 'name' => 'Anand', 'rating' => 2300];
        // result 1:0, newRating = [2404 (+4), 2293 (-7)]
        // result 0:1, newRating = [2394 (-6), 2313 (+13)]

        $players[0] = ['id' => 1, 'name' => 'Topalov', 'rating' => 2740];
        $players[1] = ['id' => 2, 'name' => 'Anand', 'rating' => 2300];
        //result 1:0, newRating = [2741 (+1), 2299 (-1)]
        //result 0:1, newRating = [2731 (-9), 2319 (+19)]

        $players[0] = ['id' => 1, 'name' => 'Anand', 'rating' => 2300];
        $players[1] = ['id' => 2, 'name' => 'New', 'rating' => 1400];
        //result 1:0, newRating = [2300, 1400] 0.01
        //result 0:1, newRating = [2280 (-20), 1440 (+40)]
        //result 0.5:0.5, newRating = [2290 (-10), 1420 (+20)]

        $this->assertTrue([2702, 2448] == $game->calculateRating());
    }
}
