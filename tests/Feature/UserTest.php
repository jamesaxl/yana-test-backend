<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Provider;
use App\Models\Product;
use App\Models\Role;
use Laravel\Passport\Passport;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use RoleSeeder;
use Faker\Factory as Faker;

class UserTest extends TestCase
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
     * Testing users table.
     *
     * @return void
     */
    public function testUserTable()
    {
          $users = User::factory()->count(10)
              ->has(Category::factory()->count(20))
              ->has(Provider::factory()->count(40))
              ->has(Product::factory()->count(10))
              ->create();

          $this->assertDatabaseCount('users', 10);
          $this->assertDatabaseCount('categories', 200);
          $this->assertDatabaseCount('providers', 400);
          $this->assertDatabaseCount('products', 100);
    }

    /**
     * Testing user requests.
     *
     * @return void
     */
    public function testUserRequest()
    {
        $faker = Faker::create('App\Models\User');
        Passport::actingAs(
            User::factory()->create([
                'is_enabled' => true,
                'role_id' => 1,
            ]),
            ['store-user']
        );

        $role = Role::inRandomOrder()->first();
        $password = Hash::make(Str::random(rand(8,100)));
        $fields = [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
            'role_id' => $role->id,
        ];

        // With required fields
        $storeOk = $this->json('post', '/api/user/store', $fields);
        $storeOk->assertStatus(200);

        // without required fields status return 422
        $storeRequired = $this->json('post', '/api/user/store');
        $storeRequired->assertStatus(422);

        // with email that already exist return status 422
        $storeUnique = $this->json('post', '/api/user/store', $fields);
        $storeUnique->assertStatus(422);

        $getAllOk = $this->json('get', '/api/user/all');
        $getAllOk->assertStatus(200);

        $user = User::inRandomOrder()->first();

        $getOk = $this->json('get', '/api/user/show/'.$user->id);
        $getOk->assertStatus(200);

        $updateOk =  $this->json('post', '/api/user/update/'.$user->id,
            ['email' => $faker->unique()->safeEmail]);
        $updateOk->assertStatus(200);

        $deleteOk = $this->json('delete', '/api/user/delete/'.$user->id);
        $deleteOk->assertStatus(200);

        // id does not exist return status 400
        $getNotExist = $this->json('get', '/api/user/show/'.$user->id);
        $getNotExist->assertStatus(400);

        // id does not exist return status 400
        $updateNotExist =  $this->json('post', '/api/user/update/'.$user->id,
            ['email' => $faker->unique()->safeEmail]);
        $updateNotExist->assertStatus(400);

        // id does not exist return status 400
        $deleteNotExist = $this->json('delete', '/api/user/delete/'.$user->id);
        $deleteNotExist->assertStatus(400);
    }
}
