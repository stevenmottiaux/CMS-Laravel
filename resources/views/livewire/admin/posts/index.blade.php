<?php

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Title('List Posts'), Layout('components.layouts.admin')] class extends Component {

	public function headers(): array
	{
		$headers = [['key' => 'title', 'label' => __('Title')]];

		if (Auth::user()->isAdmin()) {
			$headers = array_merge($headers, [['key' => 'user_name', 'label' => __('Author')]]);
		}

		return array_merge($headers, [['key' => 'category_title', 'label' => __('Category')], ['key' => 'comments_count', 'label' => __('')], ['key' => 'active', 'label' => __('Published')], ['key' => 'date', 'label' => __('Date')]]);
	}

	public function posts()
	{
		return Post::query()
			->select('id', 'title', 'slug', 'category_id', 'active', 'user_id', 'created_at', 'updated_at')
			->when(Auth::user()->isAdmin(), fn (Builder $q) => $q->withAggregate('user', 'name'))
			->when(!Auth::user()->isAdmin(), fn (Builder $q) => $q->where('user_id', Auth::id()))
			->withAggregate('category', 'title')
			->withcount('comments')
			->latest()
            ->get();
	}

	public function with(): array
	{
		return [
			'posts'   => $this->posts(),
			'headers' => $this->headers(),
		];
	}
}; ?>

<div>
    <x-header title="{{ __('Posts') }}" separator progress-indicator>
        <x-slot:actions>
            <x-button label="{{ __('Add a post') }}" class="btn-outline lg:hidden" link="#" />
            <x-button icon="s-building-office-2" label="{{ __('Dashboard') }}" class="btn-outline lg:hidden"
                link="{{ route('admin') }}" />
        </x-slot:actions>
    </x-header>

    @if ($posts->count() > 0)
        <x-card>
            <x-table striped :headers="$headers" :rows="$posts" link="#" >
                @scope('header_comments_count', $header)
                    {{ $header['label'] }}
                    <x-icon name="c-chat-bubble-left" />
                @endscope

                @scope('cell_user.name', $post)
                    {{ $post->user->name }}
                @endscope
                @scope('cell_category.title', $post)
                    {{ $post->category->title }}
                @endscope
                @scope('cell_comments_count', $post)
                    @if ($post->comments_count > 0)
                        <x-badge value="{{ $post->comments_count }}" class="badge-primary" />
                    @endif
                @endscope
                @scope('cell_active', $post)
                    @if ($post->active)
                        <x-icon name="o-check-circle" />
                    @endif
                @endscope
                @scope('cell_date', $post)
                    @lang('Created') {{ $post->created_at->diffForHumans() }}
                    @if ($post->updated_at != $post->created_at)
                        <br>
                        @lang('Updated') {{ $post->updated_at->diffForHumans() }}
                    @endif
                @endscope
                @scope('actions', $post)
                    <x-popover>
                        <x-slot:trigger>
                            <x-button icon="o-trash" wire:click="deletePost({{ $post->id }})"
                                wire:confirm="{{ __('Are you sure to delete this post?') }}" spinner
                                class="text-red-500 btn-ghost btn-sm" />
                        </x-slot:trigger>
                        <x-slot:content class="pop-small">
                            @lang('Delete')
                        </x-slot:content>
                    </x-popover>
                @endscope
            </x-table>
        </x-card>
    @endif
</div>