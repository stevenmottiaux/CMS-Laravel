<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
			[
				'name'       => 'Admin',
				'email'      => 'admin@example.com',
				'role'       => 'admin',
				'created_at' => Carbon::now()->subYears(3),
				'updated_at' => Carbon::now()->subYears(3),
			],
			[
				'name'       => 'Redac',
				'email'      => 'redac@example.com',
				'role'       => 'redac',
				'created_at' => Carbon::now()->subYears(3),
				'updated_at' => Carbon::now()->subYears(3),
			],
			[
				'name'       => 'User',
				'email'      => 'user@example.com',
				'role'       => 'user',
				'created_at' => Carbon::now()->subYears(2),
				'updated_at' => Carbon::now()->subYears(2),
			],
		];

        foreach ($users as $userData) {
			User::factory()->create($userData);
		}
    }
}
