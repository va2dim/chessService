<?php

namespace App\Http\Controllers\Api;

use App\Game;
use App\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlayersController
  extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Player::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $player = Player::create($request->all());

        return response()->json($player, 201); // Объект создан
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
/*
    public function show($id)
    {
        return Player::findOrFail($id);
    }
*/

    public function show(Player $player)
    {
        return $player;
    }

    public function showRating($id)
    {
        $rating = Player::findOrFail($id)->rating;
        return compact('rating');
    }

    public function showGames($id, Request $request)
    {

        /* // nested
        $requestedEmbeds = explode(',', $request->input('embed', ''));

        $possibleRelationships = [
          'games' => 'games',
          //'' => '',
        ];
        $eagerLoad = array_keys(array_intersect($possibleRelationships, $requestedEmbeds));
*/

        $player = Player::findOrFail($id);
/*
        var_dump($player);
        die;
*/
        //$game = Game::findOrFail(1);

        //var_dump($player->name);
        //var_dump($player->games);


        $games = $player->games;
        //$games[] = $games->winner;
        /*
        var_dump($games);
        die;
*/



        //$games = $player->with($eagerLoad)->get();
        //return (new PlayersTransformer(Player::findOrFail($id), $embeds));



        if (!empty($games)) {
            return response()->json($games);
        } else {
            return response()->json(null, 204);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $player = Player::findOrFail($id);


        $player->update($request->all());

        return response()->json($player, 200); //OK
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return response()->json(null, 204); // Отсутствует контент (действие выполнено успешно, но возвращать нечего).
    }
}
