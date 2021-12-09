<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(50)
            ->hasNotes(rand(0, 50))
            ->create();

        User::factory()
            ->hasNotes(27)
            ->create([
                'email' => 'admin@email.com'
            ]);
    }
}
