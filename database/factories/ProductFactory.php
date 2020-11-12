<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Provider;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = Category::inRandomOrder()->first();
        $provider = Provider::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'name' => $this->faker->name,
            'price' => rand(100, 200),
            'quantity' => rand(100, 200),
            'category_id' => $category ? $category->id : Category::factory(),
            'add_by_id' => $user ? $user->id : User::factory(),
            'provider_id' => $provider ? $provider->id : Provider::factory(),
        ];
    }
}
