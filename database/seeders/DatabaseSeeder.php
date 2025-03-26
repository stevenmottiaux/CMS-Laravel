<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\PageSeeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\FooterSeeder;
use Database\Seeders\CommentSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,            
            PageSeeder::class,
            MenuSeeder::class,
            FooterSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
