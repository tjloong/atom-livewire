<div class="p-4 grid gap-6 md:grid-cols-2">
    <x-form.select.owner
        wire:model="inputs.owned_by"
    />

    <x-form.select.label label="Label"
        wire:model="inputs.labels"
        type="document"
        multiple
    />
</div>
