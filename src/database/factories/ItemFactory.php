<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    public function definition(): array
    {
        return [
            'item_name' => $this->faker->word,
            'item_image' => $this->faker->word,
            'brand' => $this->faker->word,
            'color' => $this->faker->colorName,
            'description' => $this->faker->sentence,
            'condition' => $this->faker->word,
            'price' => $this->faker->randomNumber(4),
        ];
    }
}
