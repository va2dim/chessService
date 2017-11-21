<?php

use Illuminate\Database\Seeder;

class PlayersTableSeeder
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

        foreach(range(1, 15) as $index) {
            DB::table('players')->insert([
              'name' => $faker->name,
              'rating' => mt_rand(1200, 2890),
            ]);
        }

    }
}
