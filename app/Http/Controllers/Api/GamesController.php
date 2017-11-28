<?php

namespace App\Http\Controllers\Api;

use App\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Class
 *
 *
 * @package App\Http\Controllers\Api
 *
 * @return response()->json(data, HTTP Status) // возвращать явно JSON data а также HTTP код, который будет обрабатываться клиентом.
 */
class GamesController
    extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $games = Game::all();
        $games = Game::with('players')->get();
        return response()->json($games, 200);
    }


     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $game = Game::create($request->all());
        $game->players()->attach($request->players_id);

        if(!empty($game->winner_id)) { // добавить проверку на наличие relationship
            $this->updateRatings($game);
        }
        $game = Game::with('players')->where('id', $game->id)->first();

        return response()->json($game, 201); // Объект создан
    }


    /**
     * Display the specified resource.
     *
     * @param  Game $game
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = Game::with('players')->where('id', $id)->first();
        return response()->json($game, 200);
    }


    public function showGamesStartedInDatetimeRange($range)
    {
        //$range = '2010-01-01 00:00:00&2015-01-01 00:00:00'; //Postman определяет , как окончание значения; array значений также не дает задать
        $range = explode('&', $range);


        if(!isset($range[1])) {
            $range[1] = date ('Y-m-d H:i:s', time());
        }

        $games = Game::where('startDatetime', '>', $range[0])
          ->where('startDatetime', '<', $range[1])
          ->get();

        return response()->json($games, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        $game->update($request->all());

        return response()->json($game, 200); //OK
    }
    */

    public function update(Request $request, Game $game)
    {
        if(!empty($request->winner_id) && ($request->winner_id != $game->winner_id)) { // добавить проверку на наличие relationship
            $this->updateRatings($game);
        }
        $game->update($request->all());

        //$request->players_id = [10, 5];
        //var_dump($request->players_id);        die;
        if (!empty($request->players_id)) {
            $game->players()->sync($request->players_id);
        }



        $game = Game::with('players')->where('id', $game->id)->first();
        return response()->json($game, 200);
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return response()->json(null, 204); // Отсутствует контент (действие выполнено успешно, но возвращать нечего).
    }
    */

    public function destroy(Game $game)
    {
        $game->delete();
        $game->players()->detach(); // удаляем записиси в pivot table; $game->players()->delete() - удаляет игроков, но не трогает записи в pivot table

        return response()->json(null, 204);
    }


    public function calculateRating(Game $game)
    {
        $game->calculateRating();
    }

    public function updateRatings(Game $game)
    {
        $playersNewRatings = $game->calculateRating();

        $players = $game->players;

        for($i = 0; $i<2; $i++) {
            $players[$i]->rating = $playersNewRatings[$i];
            $players[$i]->save();
        }

        return $players;
    }
/*
    public function calculateRating()
    {
        $this->calculateRating();
    }

    public function updateRatings()
    {
        $playersNewRatings = $this->calculateRating();

        $players = $this->players;

        for($i = 0; $i<2; $i++) {
            $players[$i]->rating = $playersNewRatings[$i];
            $players[$i]->save();
        }

        return $players;
    }

*/
}
