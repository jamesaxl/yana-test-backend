<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use RoleSeeder;
use Faker\Factory as Faker;
use Laravel\Passport\Passport;

class CategoryTest extends TestCase
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
     * Testing categories table.
     *
     * @return void
     */
    public function testCategoryTable()
    {
        $categories = Category::factory()->count(50)
            ->create();
        $this->assertDatabaseCount('categories', 50);
    }

    /**
     * Testing category requests.
     *
     * @return void
     */
    public function testCategoryRequest()
    {
        $faker = Faker::create('App\Models\Catgory');
        Passport::actingAs(
            User::factory()->create([
                'is_enabled' => true,
                'role_id' => 1,
            ]),
            ['store-user']
        );

        $fields = [
            'name' => $faker->unique()->name,
        ];

        // With required fields
        $storeOk = $this->json('post', '/api/category/store', $fields);
        $storeOk->assertStatus(200);


        // without required fields return status 422
        $storeRequired = $this->json('post', '/api/category/store');
        $storeRequired->assertStatus(422);

        // with name that already exist return status 422
        $storeUnique = $this->json('post', '/api/category/store', $fields);
        $storeUnique->assertStatus(422);

        $getAllOk = $this->json('get', '/api/category/all');
        $getAllOk->assertStatus(200);

        $category = Category::inRandomOrder()->first();

        $getOk = $this->json('get', '/api/category/show/'.$category->id);
        $getOk->assertStatus(200);

        $updateOk =  $this->json('post', '/api/category/update/'.$category->id,
            ['name' => $faker->unique()->name]);
        $updateOk->assertStatus(200);

        $deleteOk = $this->json('delete', '/api/category/delete/'.$category->id);
        $deleteOk->assertStatus(200);


        // id does not exist return status 400
        $getNotExist = $this->json('get', '/api/category/show/'.$category->id);
        $getNotExist->assertStatus(400);

        // id does not exist return status 400
        $updateNotExist =  $this->json('post', '/api/category/update/'.$category->id,
            ['name' => $faker->unique()->name]);
        $updateNotExist->assertStatus(400);

        // id does not exist return status 400
        $deleteNotExist = $this->json('delete', '/api/category/delete/'.$category->id);
        $deleteNotExist->assertStatus(400);
    }
}
