<?php

namespace Jhumanj\LaravelModelStats\Database\Factories;

use Jhumanj\LaravelModelStats\Models\Dashboard;
use Illuminate\Database\Eloquent\Factories\Factory;

class DashboardFactory extends Factory
{
    protected $model = Dashboard::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->name,
            'description' => $this->faker->sentence,
            'body'        => '{"widgets":[]}',
        ];
    }
}
