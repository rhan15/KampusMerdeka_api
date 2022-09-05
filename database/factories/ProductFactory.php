<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->slug(),
            'price' => mt_rand(10000, 999000),
            'description' => fake()->paragraph(),
            'photo' => $this->faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
            'store_id' => Store::inRandomOrder()->first()->id,
            
        ];
    }
}
