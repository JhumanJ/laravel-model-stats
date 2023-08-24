<?php

namespace Jhumanj\LaravelModelStats\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jhumanj\LaravelModelStats\Models\Dashboard;

class DashboardFactory extends Factory
{
    protected $model = Dashboard::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'body' => '{"widgets":[]}',
        ];
    }
}
