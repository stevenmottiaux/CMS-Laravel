<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
			['slug' => 'terms', 'title' => 'Terms'],
			['slug' => 'privacy-policy', 'title' => 'Privacy Policy'],
		];

		foreach ($items as $item) {
			Page::factory()->create([
				'title'     => $item['title'],
				'seo_title' => 'Page ' . $item['title'],
				'slug'      => $item['slug'],
				'active'    => true,
			]);
		}
    }
}
