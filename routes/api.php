<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('players/{id}/rating', 'Api\PlayersController@showRating');
Route::get('players/{id}/games', 'Api\PlayersController@showGames');
//Route::get('games/{game}/cr', 'Api\GamesController@updateRatings');

Route::get('games/datetimerange/{startDatetime},{endDatetime}', 'Api\GamesController@showGamesStartedInDatetimeRange');
Route::get('games/datetimerange/{startDatetime}', 'Api\GamesController@showGamesStartedInDatetimeRange');










//R Route::resource('resource_name', 'resource_nameController') - instead of manual list of routes for each resource action
Route::namespace('Api')->group(function () {
    Route::resource('games', 'GamesController');
    Route::resource('players', 'PlayersController');
});

//Route::get('players/{id}', 'Api\PlayersController@showGames');

/*
}
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    Route::resource('games', 'GamesController');
    //Route::resource('players', 'PlayersController');
});
*/
/*
// set headers on your API responses
Route::get('games', function () {
    return response(\App\Game::all())
      ->header('X-Greatness-Index', 9);
});

// Reading a request header
Route::get('games', function (Request $request) {
    echo $request->header('Accept');
});


Route::get('players', function () {
    return response(\App\Player::all())
      ->header('X-Greatness-Index', 9);
});

Route::get('players', function (Request $request) {
    echo $request->header('Accept');
});

*/