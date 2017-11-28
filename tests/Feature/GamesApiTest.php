<?php


namespace Tests;


use App\Game;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GamesApiTest
  extends TestCase
{
    use WithoutMiddleware, DatabaseMigrations;

    public function testGameAreCreatedCorrectly()
    {
        /*
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        */
        $payload = [
          'startDatetime' => '2017-11-23 10:00:00',
          'endDatetime' => '2017-11-23 10:45:00',
          'winner_id' => 1,
        ];

        $this->json('POST', '/api/games', $payload)
          ->assertStatus(201)
          ->assertJson([
            'id' => 1,
            'startDatetime' => '2017-11-23 10:00:00',
            'endDatetime' => '2017-11-23 10:45:00',
            'winner_id' => 1
          ]);
    }

    public function testGameAreUpdatedCorrectly()
    {
        $game = factory(Game::class)->create([
          'startDatetime' => '2017-11-23 10:00:00',
          'endDatetime' => '2017-11-23 10:45:00',
          'winner_id' => 1,
        ]);

        $payload = [
          'startDatetime' => '2017-11-20 17:00:00',
          'endDatetime' => '2017-11-20 18:45:00',
          'winner_id' => 7,
        ];

        $response = $this->json('PUT', '/api/games/' . $game->id, $payload)
          ->assertStatus(200)
          ->assertJson([
            'id' => 1,
            'startDatetime' => '2017-11-20 17:00:00',
            'endDatetime' => '2017-11-20 18:45:00',
            'winner_id' => 7,
          ]);
    }

    public function testGameAreDeletedCorrectly()
    {
        $game = factory(Game::class)->create([
          'startDatetime' => '2017-11-23 10:00:00',
          'endDatetime' => '2017-11-23 10:45:00',
          'winner_id' => 1,
        ]);

        $this->json('DELETE', '/api/games/' . $game->id, [])
          ->assertStatus(204);
    }

    public function testGameAreListedCorrectly()
    {
        $game[] = factory(Game::class)->create([
          'startDatetime' => '2017-11-23 10:00:00',
          'endDatetime' => '2017-11-23 10:45:00',
          'winner_id' => 1,
        ]);

        $game[] = factory(Game::class)->create([
          'startDatetime' => '2017-11-20 17:00:00',
          'endDatetime' => '2017-11-20 18:45:00',
          'winner_id' => 7,
        ]);

        $this->json('GET', '/api/games')
          ->assertStatus(200)
          ->assertJson([
            [
              'id' => 1,
              'startDatetime' => '2017-11-23 10:00:00',
              'endDatetime' => '2017-11-23 10:45:00',
              'winner_id' => 1,
            ],
            [
              'id' => 2,
              'startDatetime' => '2017-11-20 17:00:00',
              'endDatetime' => '2017-11-20 18:45:00',
              'winner_id' => 7,
            ]
          ])
          ->assertJsonStructure([
            '*' => ['id', 'startDatetime', 'endDatetime', 'winner_id'],
          ]);
    }
    /*
    public function test_it_gets_all_games()
    {
        //$this->be(factory(User::class)->create());
        $game1 = factory(Game::class)->create();
        $game2 = factory(Game::class)->create();
        $this->visit('api/games');
        //var_dump($this);
        $this->seeJson([
          'startDatetime' => $game1->startDatetime,
          'endDatetime' => $game1->endDatetime,
        ]);
        $this->seeJson([
          'winner_id' => $game2->winner_id,
        ]);
    }
    */
}