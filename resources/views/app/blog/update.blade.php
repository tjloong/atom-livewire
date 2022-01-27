<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Update Blog" back>
        @if (Route::has('blogs'))
            <x-button color="gray" icon="show" href="{{ route('blogs', [$blog, 'preview' => true]) }}" target="_blank">
                Preview
            </x-button>
        @endif

        <x-button color="red" icon="trash" inverted x-on:click="$dispatch('confirm', {
            title: 'Delete Blog',
            message: 'Are you sure to delete this blog?',
            type: 'error',
            onConfirmed: () => $wire.delete(),
        })">
            Delete
        </x-button>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                <x-sidenav item href="{{ route('blog.update', [$blog->id, 'content']) }}">Blog Content</x-sidenav>
                <x-sidenav item href="{{ route('blog.update', [$blog->id, 'settings']) }}">Settings</x-sidenav>
                <x-sidenav item href="{{ route('blog.update', [$blog->id, 'seo']) }}">SEO</x-sidenav>
            </x-sidenav>
        </div>
    
        <div class="md:col-span-9">
            <div>
                @livewire('atom.blog.form.' . $tab, ['blog' => $blog], key($tab))
            </div>
        </div>
    </div>
</div>