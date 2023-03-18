<x-box header="Product Images">
    <x-slot:buttons>
        @if ($checkboxes)
            <x-button.delete inverted size="sm"
                label="Delete Images ({{ count($checkboxes) }})"
                title="Bulk Delete Product Images"
                message="This will DELETE the selected {{ count($checkboxes) }} image(s). Are you sure?"
            />
        @endif
    </x-slot:buttons>

    <div class="p-5 flex flex-col gap-4">
        @if ($product->images->count())
            <x-form.sortable wire:sorted="sort" class="grid gap-4 grid-cols-2 md:grid-cols-6">
                @foreach ($product->images()->oldest('seq')->latest('id')->get() as $img)
                    <div data-sortable-id="{{ $img->id }}" class="rounded-lg flex items-center justify-center">
                        <x-thumbnail :file="$img" class="cursor-move">
                            <x-slot:buttons>
                                <div wire:click="checkbox(@js($img->id))" class="cursor-pointer">
                                    <x-icon name="circle-check" class="{{ 
                                        in_array($img->id, $checkboxes) ? 'text-green-500' : 'text-white' }}
                                    "/>
                                </div>
                            </x-slot:buttons>
                        </x-thumbnail>
                    </div>
                @endforeach
            </x-form.sortable>
        @endif

        <x-form.file wire:model="files" accept="image/*" :label="false" multiple/>
    </div>
</x-box>
