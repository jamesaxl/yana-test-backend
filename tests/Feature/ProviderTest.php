<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Provider;
use App\Models\User;
use RoleSeeder;
use Faker\Factory as Faker;
use Laravel\Passport\Passport;

class ProviderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * this function contains the creation of Role seed
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    /**
     * Testing providers table.
     *
     * @return void
     */
    public function testProverTable()
    {
        $Categories = Provider::factory()->count(50)
            ->create();

        $this->assertDatabaseCount('providers', 50);
    }

    /**
     * Testing provider requests.
     *
     * @return void
     */
    public function testProviderRequest()
    {
        $faker = Faker::create('App\Models\Procider');
        Passport::actingAs(
            User::factory()->create([
                'is_enabled' => true,
                'role_id' => 1,
            ]),
            ['store-user']
        );

        $fields = [
            'name' => $faker->unique()->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => '01'.rand(100000000, 999999999),
        ];

        // With required fields
        $storeOk = $this->json('post', '/api/provider/store', $fields);
        $storeOk->assertStatus(200);

        // without fields fields return status 422
        $storeRequired = $this->json('post', '/api/provider/store');
        $storeRequired->assertStatus(422);

        // with name that exist return status 422
        $storeUnique = $this->json('post', '/api/provider/store', $fields);
        $storeUnique->assertStatus(422);

        $getAllOk = $this->json('get', '/api/provider/all');
        $getAllOk->assertStatus(200);

        $provider = Provider::inRandomOrder()->first();

        $getOk = $this->json('get', '/api/provider/show/'.$provider->id);
        $getOk->assertStatus(200);

        $updateOk =  $this->json('post', '/api/provider/update/'.$provider->id,
            ['name' => $faker->unique()->name]);
        $updateOk->assertStatus(200);

        $deleteOk = $this->json('delete', '/api/provider/delete/'.$provider->id);
        $deleteOk->assertStatus(200);

        // id does not exist return status 400
        $getNotExist = $this->json('get', '/api/provider/show/'.$provider->id);
        $getNotExist->assertStatus(400);

        // id does not exist return status 400
        $updateNotExist =  $this->json('post', '/api/provider/update/'.$provider->id,
            ['name' => $faker->unique()->name]);
        $updateNotExist->assertStatus(400);

        // id does not exist return status 400
        $deleteNotExist = $this->json('delete', '/api/provider/delete/'.$provider->id);
        $deleteNotExist->assertStatus(400);
    }
}
