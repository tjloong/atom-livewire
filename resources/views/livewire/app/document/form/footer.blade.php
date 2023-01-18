<div class="p-4 grid gap-6">
    <x-form.textarea label="Note"
        wire:model.debounce.400ms="inputs.note"
    />

    <x-form.text label="Footer" 
        wire:model.debounce.400ms="inputs.footer"
    />
</div>
