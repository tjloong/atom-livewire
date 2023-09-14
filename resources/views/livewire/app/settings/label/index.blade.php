<div class="max-w-screen-md">
    <x-heading :title="$this->title">
        <x-button icon="add"
            label="Create New"
            wire:click="$emit('createLabel')"/>
    </x-heading>

    <x-box>
        @if ($this->labels->count())
            @livewire('app.settings.label.listing', ['labels' => $this->labels], key(uniqid()))
        @else
            <x-no-result
                :title="'No '.str($this->title)->singular()->headline()"
                :subtitle="'You do not have any '.str($this->title)->singular()->headline()->lower()"
            />
        @endif
    </x-box>

    @livewire('app.settings.label.update', compact('type'), key('update'))
</div>