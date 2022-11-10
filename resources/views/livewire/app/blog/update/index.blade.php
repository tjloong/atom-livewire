<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Update Blog" back>
        <x-button color="gray" icon="show" target="_blank" 
            label="Preview"
            href="/blog/{{ $blog->slug }}?preview=1"
        />

        <x-button.delete inverted
            title="Delete Blog"
            message="Are you sure to delete this blog?"
        />
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    <x-sidenav.item :icon="false"
                        :name="data_get($item, 'slug')"
                        :label="data_get($item, 'label') ?? str(data_get($item, 'slug'))->headline()"
                    />
                @endforeach
            </x-sidenav>
        </div>
    
        <div class="md:w-3/4">
            @livewire(lw('app.blog.update.'.$tab), compact('blog'), key($tab))
        </div>
    </div>
</div>