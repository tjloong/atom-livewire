<x-box header="Product Images">
    <div class="p-4">
        <x-form.file accept="image/*" :label="false" multiple sortable
            wire:model="images"
            wire:sorted="sort"
        />
    </div>
</x-box>
