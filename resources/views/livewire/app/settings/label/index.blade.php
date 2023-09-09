<div class="max-w-screen-md">
    <x-heading :title="$this->title" 2xl>
        <x-button icon="add"
            label="Create New"
            wire:click="update({ type: '{{ $type }}' })"/>
    </x-heading>

    <x-box>
        @if ($this->labels->count())
            @livewire('app.settings.label.listing', ['labels' => $this->labels], key(uniqid()))
        @else
            <x-noresult
                :title="'No '.str($this->title)->singular()->headline()"
                :subtitle="'You do not have any '.str($this->title)->singular()->headline()->lower()"
            />
        @endif
    </x-box>

    @livewire('app.settings.label.update', key('update'))
</div>