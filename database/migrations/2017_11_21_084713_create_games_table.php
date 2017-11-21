<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('startDatetime')->nullable();
            $table->timestamp('endDatetime')->nullable();
            $table->integer('winner_id');
        });

        Schema::create('games_to_players', function (Blueprint $table) {
            $table->integer('game_id');
            $table->integer('player_id');
        });

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
        Schema::dropIfExists('games_to_players');
    }
}
