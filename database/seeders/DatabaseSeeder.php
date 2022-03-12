<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        Todo::create([
            'task' => 'This is an example task',
            'deadline' => Carbon::now()->addYear()
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
