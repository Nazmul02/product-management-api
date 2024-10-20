<?php
namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true), // Generates a 3-word product name
            'description' => $this->faker->sentence, // Generates a random sentence
            'price' => $this->faker->randomFloat(2, 10, 500), // Random price between 10 and 500
            'in_stock' => $this->faker->boolean, // True or false for stock availability
            'stock_count' => $this->faker->numberBetween(1, 100), // Stock count between 1 and 100
            'images' => ['https://via.placeholder.com/150'], // Placeholder image
            'user_id' => User::factory(), // User relation
        ];
    }
}
