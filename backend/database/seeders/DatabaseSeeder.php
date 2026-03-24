<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //criar 4 admins
        User::factory()->count(2)->create([
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email'=>'admin@mail.com',
            'password'=> Hash::make('123'),
            'role' => 'admin',
        ]);


        User::factory()->count(8)->create([
            'role' => 'worker',
        ]);

        User::factory()->create([
            'name' => 'worker',
            'email'=>'worker@mail.com',
            'password'=> Hash::make('123'),
            'role' => 'worker',
        ]);

        Task::factory()->count(30)->create();

    }
}
