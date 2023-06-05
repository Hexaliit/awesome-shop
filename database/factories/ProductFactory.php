<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(20),
            'description' => $this->faker->sentence(8),
            'price' => $this->faker->numberBetween(100 , 500),
            'image' => 'avatar.png',
            'category_id' => $this->faker->numberBetween(1,3)
            /*function(){
                Category::factory()->create()->where('category_id' , '!=' , null)->id;
            }*/
        ];
    }
}
