<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nbrCategories = 3;

		$this->createPost(1, 1);
		$this->createPost(2, rand(1, $nbrCategories));
		$this->createPost(3, 1);
		$this->createPost(4, 1);
		$this->createPost(5, rand(1, $nbrCategories));
		$this->createPost(6, 1);
		$this->createPost(7, 1);
		$this->createPost(8, rand(1, $nbrCategories));
		$this->createPost(9, rand(1, $nbrCategories));
    }

    protected function createPost(int $id, int $categoryId): Post
    {
        $months = ['03', '03', '03', '04', '04', '06', '06', '06', '06'];

		$date = generateRandomDateInRange('2022-01-01', '2025-07-01');

		$postId = "Post {$id}";

		return Post::factory()->create([
			'title'       => $postId,
			'seo_title'   => $postId,
			'slug'        => Str::of($postId)->slug('-'),
			'user_id'     => rand(1, 2),
			'image'       => '2025/' . $months[$id - 1] . '/img0' . $id . '.jpg',
			'category_id' => $categoryId,
			'created_at'  => $date,
			'updated_at'  => $date,
			'pinned'      => 5 == $id,
		]);
    }
}
