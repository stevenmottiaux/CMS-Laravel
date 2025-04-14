<?php

use Illuminate\Support\Facades\{Auth, Session};
use Livewire\Volt\Component;

new class() extends Component {
	public function logout(): void
	{
		Auth::guard('web')->logout();

		Session::invalidate();
		Session::regenerateToken();

		$this->redirect('/');
	}
}; ?>

<div>
    <x-menu activate-by-route>
        <x-menu-separator />
        <x-list-item :item="Auth::user()" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
            <x-slot:actions>
                <x-button icon="o-power" wire:click="logout" class="btn-circle btn-ghost btn-xs"
                    tooltip-left="{{ __('Logout') }}" no-wire-navigate />
            </x-slot:actions>
        </x-list-item>
        <x-menu-separator />
        <x-menu-item title="{{ __('Dashboard') }}" icon="s-building-office-2" link="{{ route('admin') }}" />
        <x-menu-sub title="{{ __('Posts') }}" icon="s-document-text">
    <x-menu-item title="{{ __('All posts') }}" link="{{ route('posts.index') }}" />
</x-menu-sub>
        <x-menu-item icon="m-arrow-right-end-on-rectangle" title="{{ __('Go on site') }}" link="/" />
        <x-menu-item>
            <x-theme-toggle />
        </x-menu-item>
    </x-menu>
</div>