<?php

namespace Database\Factories;

use App\Models\PayItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PayItemFactory extends Factory
{
    protected $model = PayItem::class;

    public function definition()
    {
        return [
            'external_id' => Str::random(10),
            'hours_worked' => $this->faker->numberBetween(1, 100),
            'pay_rate' => $this->faker->randomFloat(2, 10, 100),
            'pay_date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
