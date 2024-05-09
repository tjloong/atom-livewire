<div class="max-w-screen-md">
    <x-heading title="{!! tr('app.label.label') !!} - {!! str($slug)->headline() !!}">
        <x-button label="app.label.add-new" icon="add" wire:click="$emit('createLabel', {
            type: '{{ $slug }}',
        })"/>
    </x-heading>

    <x-box>
        @livewire('app.label.listing', ['labels' => $this->labels], key(uniqid()))
    </x-box>
</div>