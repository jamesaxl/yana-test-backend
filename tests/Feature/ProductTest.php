<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Provider;
use RoleSeeder;
use Faker\Factory as Faker;
use Laravel\Passport\Passport;

class ProductTest extends TestCase
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
     * Testing products table.
     *
     * @return void
     */
    public function testProductTable()
    {
        $products = Product::factory()->count(50)
            ->create();
        $this->assertDatabaseCount('products', 50);
    }

    /**
     * Testing products requests.
     *
     * @return void
     */
    public function testProductRequest()
    {
        $faker = Faker::create('App\Models\Product');
        Passport::actingAs(
            User::factory()->create([
                'is_enabled' => true,
                'role_id' => 1,
            ]),
            ['store-user']
        );

        $category = Category::factory()->count(1)
            ->create();
        $this->assertDatabaseCount('categories', 1);

        $provider = Provider::factory()->count(1)
            ->create();
        $this->assertDatabaseCount('providers', 1);

        $fields = [
            'name' => $faker->unique()->name,
            'price' => $faker->randomDigit,
            'quantity' => $faker->randomDigit,
            'category_id' => $category->first()->id,
            'provider_id' => $provider->first()->id,
        ];

        // With required fields
        $storeOk = $this->json('post', '/api/product/store', $fields);
        $storeOk->assertStatus(200);

        // without required fields return status 422
        $storeRequired = $this->json('post', '/api/product/store');
        $storeRequired->assertStatus(422);

        $getAllOk = $this->json('get', '/api/product/all');
        $getAllOk->assertStatus(200);

        $product = Product::inRandomOrder()->first();

        $getOk = $this->json('get', '/api/product/show/'.$product->id);
        $getOk->assertStatus(200);

        $updateOk =  $this->json('post', '/api/product/update/'.$product->id,
            ['name' => $faker->unique()->name]);
        $updateOk->assertStatus(200);

        $deleteOk = $this->json('delete', '/api/product/delete/'.$product->id);
        $deleteOk->assertStatus(200);

        // id does not exist return status 400
        $getNotExist = $this->json('get', '/api/product/show/'.$product->id);
        $getNotExist->assertStatus(400);

        // id does not exist return status 400
        $updateNotExist =  $this->json('post', '/api/product/update/'.$product->id,
            ['name' => $faker->unique()->name]);
        $updateNotExist->assertStatus(400);

        // id does not exist return status 400
        $deleteNotExist = $this->json('delete', '/api/product/delete/'.$product->id);
        $deleteNotExist->assertStatus(400);
    }
}
