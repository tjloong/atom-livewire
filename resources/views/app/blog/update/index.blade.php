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
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    <x-sidenav.item :name="data_get($item, 'slug')">
                        {{ data_get($item, 'label') ?? str(data_get($item, 'slug'))->headline() }}
                    </x-sidenav.item>
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