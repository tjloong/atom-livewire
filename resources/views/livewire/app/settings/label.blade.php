<div class="max-w-screen-md">
    <x-heading title="{!! tr('app.label.label:2') !!} - {!! str($type)->headline() !!}"/>

    <x-box>
        @livewire('app.label.listing', [
            'labels' => $this->labels,
        ], key('label-listing'))
    </x-box>
</div>