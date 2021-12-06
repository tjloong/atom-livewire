<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Update Blog" back>
        <x-button color="gray" icon="show" href="{{ route('blog.show', [$blog, 'preview' => true]) }}" target="_blank">
            Preview
        </x-button>

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
                <x-sidenav.item name="content">Blog Content</x-sidenav.item>
                <x-sidenav.item>Settings</x-sidenav.item>
                <x-sidenav.item>SEO</x-sidenav.item>
            </x-sidenav>
        </div>
    
        <div class="md:col-span-9">
            <div>
                @if ($tab === 'content')
                    @livewire('app.blog.form.content', ['blog' => $blog], key($tab))
                @endif
            </div>

            <div>
                @if ($tab === 'settings')
                    @livewire('app.blog.form.settings', ['blog' => $blog], key($tab))
                @endif
            </div>

            <div>
                @if ($tab === 'seo')
                    @livewire('app.blog.form.seo', ['blog' => $blog], key($tab))
                @endif
            </div>
        </div>
    </div>
</div>