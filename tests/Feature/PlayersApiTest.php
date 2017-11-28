<?php


namespace Tests;


use App\Player;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PlayersApiTest
  extends TestCase
{
    use WithoutMiddleware, DatabaseMigrations;

    public function testPlayerAreCreatedCorrectly()
    {
        $payload = [
          'name' => 'Veselin Topalov',
          'rating' => 2705,
        ];

        $this->json('POST', '/api/players', $payload)
          ->assertStatus(201)
          ->assertJson([
            'id' => 1,
            'name' => 'Veselin Topalov',
            'rating' => 2705
          ]);
    }

    public function testPlayerAreUpdatedCorrectly()
    {

        $player = factory(Player::class)->create([
          'name' => 'Kasparov',
          'rating' => 2650,
        ]);

        $payload = [
          'name' => 'Karpov',
          'rating' => 2600,
        ];

        $response = $this->json('PUT', '/api/players/' . $player->id, $payload)
          ->assertStatus(200)
          ->assertJson([
            'id' => 1,
            'name' => 'Karpov',
            'rating' => 2600
          ]);
    }

    public function testGameAreDeletedCorrectly()
    {
        $player = factory(Player::class)->create([
          'name' => 'Karpov',
          'rating' => 2600,
        ]);

        $this->json('DELETE', '/api/players/' . $player->id, [])
          ->assertStatus(204);
    }

    public function testGameAreListedCorrectly()
    {
        $player[] = factory(Player::class)->create([
          'name' => 'Karpov',
          'rating' => 2600,
        ]);

        $player[] = factory(Player::class)->create([
          'name' => 'Kasparov',
          'rating' => 2650,
        ]);

        $player[] = factory(Player::class)->create([
          'name' => 'Veselin Topalov',
          'rating' => 2705,
        ]);

        $this->json('GET', '/api/players')
          ->assertStatus(200)
          ->assertJson([
            [
              'id' => 1,
              'name' => 'Karpov',
              'rating' => 2600,
            ],
            [
              'id' => 2,
              'name' => 'Kasparov',
              'rating' => 2650,
            ],
            [
              'id' => 3,
              'name' => 'Veselin Topalov',
              'rating' => 2705,
            ]
          ])
          ->assertJsonStructure([
            '*' => ['id', 'name', 'rating'],
          ]);
    }


    /*
    public function test_it_gets_all_players()
    {
        //$this->be(factory(User::class)->create());
        $player1 = factory(Player::class)->create();
        $player2 = factory(Player::class)->create();
        $this->visit('api/players');
        $this->seeJson([
          'name' => $player1->name,
          'rating' => $player1->rating
        ]);
        $this->seeJson([
          'rating' => $player2->rating
        ]);
    }

    */
}