<div class="max-w-screen-md">
    <x-heading title="{!! tr('app.label.label') !!} - {!! str($slug)->headline() !!}" lg>
        <x-button label="app.label.add-new" icon="add"
            wire:click="$emit('editLabel', {{ Js::from(['type' => $slug]) }})">
        </x-button>
    </x-heading>

    <x-box>
        <livewire:app.label.listing :labels="$this->labels" :wire:key="uniqid()"/>
    </x-box>
</div>