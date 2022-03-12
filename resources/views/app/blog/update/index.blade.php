<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Update Blog" back>
        @if (Route::has('blogs'))
            <x-button color="gray" icon="show" href="{{ route('blogs', [$blog->slug, 'preview' => true]) }}" target="_blank">
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
            <x-sidenav>
                @foreach ($tabs as $key => $val)
                    <x-sidenav item href="{{ route('app.blog.update', [$blog->id, $key]) }}">{{ $val }}</x-sidenav>
                @endforeach
            </x-sidenav>
        </div>
    
        <div class="md:col-span-9">
            @if ($component = get_livewire_component($tab, 'app/blog/update'))
                @livewire($component, compact('blog'), key($tab))
            @endif
        </div>
    </div>
</div>