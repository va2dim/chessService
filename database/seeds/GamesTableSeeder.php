<?php

use Illuminate\Database\Seeder;

class GamesTableSeeder
  extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach(range(1, 10) as $index) {

            //TODO: start,endDatetime through faker
            //$dt = $faker->dateTimeThisCentury;

            $year = mt_rand(2009, 2017);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $hour = mt_rand(0, 24);
            $minute = mt_rand(0, 60);
            $start_dt = \Carbon\Carbon::create($year, $month, $day, $hour, $minute, 0);
            $hour = $hour + mt_rand(1, 2);
            $minute = mt_rand(0, 60);
            $end_dt = \Carbon\Carbon::create($year, $month, $day, $hour, $minute, 0);

            $players = \App\Player::inRandomOrder()->limit(2)->get();
            foreach ($players as $i => $player) {
                $player_id[$i] = $player->id;
            }

            $game_id = DB::table('games')->insertGetId([
              'winner_id' => $player_id[array_rand($player_id)],
              'startDatetime' => $start_dt,
              'endDatetime' => $end_dt,
            ]);

            for($i=0; $i<2; $i++) {
                DB::table('game_player')->insert([
                  'game_id' => $game_id,
                  'player_id' => $player_id[$i]
                ]);
            }


        }

    }
}
