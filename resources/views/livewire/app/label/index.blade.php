<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$this->title">
        <x-button icon="add"
            label="Create New"
            wire:click="$emit('updateOrCreate', { type: '{{ $type }}' })"
        />
    </x-page-header>

    <x-box>
        @if ($this->labels->count())
            @livewire(atom_lw('app.label.listing'), ['labels' => $this->labels], key('listing'))
            @livewire(atom_lw('app.label.form'), key('form'))
        @else
            <x-empty-state
                :title="'No '.str($this->title)->singular()->headline()"
                :subtitle="'You do not have any '.str($this->title)->singular()->headline()->lower()"
            />
        @endif
    </x-box>
</div>