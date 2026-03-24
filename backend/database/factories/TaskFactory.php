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
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        return [
            'title' => $faker->jobTitle(),
            'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'status' => $faker->randomElement(['PENDING', 'IN_PROGRESS', 'COMPLETED','CANCELLED']),
            'priority' => $faker->randomElement(['LOW', 'MEDIUM', 'HIGH']),
            'due_date' => $faker->dateTimeBetween('-1 year', 'now'),
            'admin_id' => User::where('role', 'admin')->inRandomOrder()->first()->id,
            'worker_id'=> User::where('role', 'worker')->inRandomOrder()->first()->id,

        ];
    }
}
