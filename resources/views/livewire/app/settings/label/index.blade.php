<div class="max-w-screen-md">
    <x-heading :title="$this->title">
        <x-button icon="add"
            label="common.label.new"
            wire:click="$emit('createLabel', {{ json_encode(compact('type')) }})"/>
    </x-heading>

    <x-box>
        @if ($this->labels->count())
            @livewire('app.settings.label.listing', ['labels' => $this->labels], key(uniqid()))
        @else
            <x-no-result
                title="common.empty.result.title"
                subtitle="common.empty.result.subtitle"/>
        @endif
    </x-box>
</div>