<?php

namespace Database\Factories;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'external_id' => Str::random(10),
            'enabled' => true,
            'deduction_percentage' => 30,
        ];
    }
}
