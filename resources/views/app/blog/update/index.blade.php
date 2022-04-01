<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Update Blog" back>
        <x-button color="gray" icon="show" target="_blank" :href="route('page', ['blogs/'.$blog->slug.'?preview=1'])">
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
            <x-sidenav>
                @foreach ($this->tabs as $val)
                    <x-sidenav item :href="route('app.blog.update', [$blog->id, $val->slug])">
                        {{ $val->label ?? str()->headline($val->slug) }}
                    </x-sidenav>
                @endforeach
            </x-sidenav>
        </div>
    
        <div class="md:col-span-9">
            @if ($component = livewire_name('app/blog/update/'.$tab))
                @livewire($component, compact('blog'), key($tab))
            @endif
        </div>
    </div>
</div>