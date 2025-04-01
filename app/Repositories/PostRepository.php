<?php

namespace App\Repositories;

use App\Models\{Category, Post, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;


class PostRepository
{
	public function getPostsPaginate(?Category $category): LengthAwarePaginator
	{
		$query = $this->getBaseQuery()->orderBy('pinned', 'desc')->latest();

		if ($category) {
			$query->whereBelongsTo($category);
		}

		return $query->paginate(config('app.pagination'));
	}

	protected function getBaseQuery(): Builder
	{
		$specificReqs = [
			'mysql'  => "LEFT(body, LOCATE(' ', body, 700))",
			'sqlite' => 'substr(body, 1, 700)',
			'pgsql'  => 'substring(body from 1 for 700)',
		];

		$usedDbSystem = env('DB_CONNECTION', 'mysql');

		if (!isset($specificReqs[$usedDbSystem])) {
			throw new Exception("Base de données non supportée: {$usedDbSystem}");
		}

		$adaptedReq = $specificReqs[$usedDbSystem];

		return Post::select('id', 'slug', 'image', 'title', 'user_id', 'category_id', 'created_at', 'pinned')
			->selectRaw(
				"CASE
                    WHEN LENGTH(body) <= 300 THEN body
                    ELSE {$adaptedReq}
                END AS excerpt",
			)
			->with('user:id,name', 'category')
			->whereActive(true)
			->when(Auth::check(), function ($query) {
				$userId = Auth::id();
				$query->addSelect([
					'is_favorited' => DB::table('favorites')
						->selectRaw('1')
						->whereColumn('post_id', 'posts.id')
						->where('user_id', $userId)
						->limit(1)
				]);
			});	
	}

	public function getPostBySlug(string $slug): Post
	{

		$userId = Auth::id();

		return Post::with('user:id,name', 'category')
			->withCount('validComments')
			->withExists([
				'favoritedByUsers as is_favorited' => function ($query) use ($userId) {
					$query->where('user_id', $userId);
				},
			])
			->whereSlug($slug)->firstOrFail();
	}

	public function search(string $search): LengthAwarePaginator
	{
		return $this->getBaseQuery()
			->latest()
			->where(function ($query) use ($search) {
				$query->where('body', 'like', "%{$search}%")->orWhere('title', 'like', "%{$search}%");
			})
			->paginate(config('app.pagination'));
	}

	public function getFavoritePosts(User $user): LengthAwarePaginator
	{
		return $this->getBaseQuery()
			->whereHas('favoritedByUsers', function (Builder $query) use ($user) {
				$query->where('user_id', Auth::id());
			})
			->latest()
			->paginate(config('app.pagination'));
	}
}