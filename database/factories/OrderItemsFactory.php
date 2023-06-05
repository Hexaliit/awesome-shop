<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItems>
 */
class OrderItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => function(){
                return Order::factory()->create()->id;
            },
            'product_id' => function(){
                return Product::factory()->create()->id;
            },
            'quantity' => $this->faker->numberBetween(1,6),
            'price' => $this->faker->numberBetween(200,1000)
        ];
    }
}
