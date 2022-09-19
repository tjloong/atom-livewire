<div>
    <x-box header="Product Images">
        <x-slot:header-buttons>
            <x-button size="sm"
                label="Add Image"
                x-on:click="$dispatch('uploader-open')"
            />
        </x-slot:header-buttons>

        <div class="p-5">
            @if ($productImages->count())
                <x-form.sortable 
                    wire:sorted="sort" 
                    :config="[
                        'handle' => '.sort-handle',
                        'filter' => '.product-image-picker',
                    ]"
                    class="grid gap-4 grid-cols-2 md:grid-cols-6"
                >
                    @foreach ($productImages as $img)
                        <div 
                            class="relative pt-[100%] bg-gray-100 rounded-md shadow overflow-hidden"
                            data-sortable-id="{{ data_get($img, 'id') }}"
                        >
                            <div class="absolute inset-0 sort-handle cursor-move">
                                <img src="{{ data_get($img, 'url') }}" class="w-full h-full object-cover">
                            </div>
                            <a 
                                wire:click="remove({{ data_get($img, 'id') }})"
                                class="absolute top-2 right-2 w-8 h-8 rounded-full bg-white flex border shadow"
                            >
                                <x-icon name="xmark" size="16px" class="m-auto text-red-500"/>
                            </a>
                        </div>
                    @endforeach
                </x-form.sortable>
            @else
                <x-empty-state title="No product image" subtitle="This product do not have any images."/>
            @endif
        </div>
    </x-box>

    @livewire('atom.app.file.uploader', [
        'uid' => 'uploader',
        'title' => 'Insert Image',
        'multiple' => true,
        'accept' => ['image'],
        'sources' => ['device', 'web-image', 'library'],
    ])
</div>
