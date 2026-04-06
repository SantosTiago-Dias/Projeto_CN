<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $status = fake()->randomElement(['PENDING', 'IN_PROGRESS', 'COMPLETED', 'CANCELLED']);
        return [
            'title' => $faker->jobTitle(),
            'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'status' => $status,
            'priority' => $faker->randomElement(['LOW', 'MEDIUM', 'HIGH']),
            'outside' => $faker->boolean(50),
            'reason_cancelled' => ($status == 'CANCELLED') ? $faker->sentence() : null,
            'due_date' => $faker->dateTimeBetween('-1 year', 'now'),
            'admin_id' => User::where('role', 'admin')->inRandomOrder()->first()->id,
            'worker_id'=> User::where('role', 'worker')->inRandomOrder()->first()->id,

        ];
    }
}
