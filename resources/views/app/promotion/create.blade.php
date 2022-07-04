<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Create Promotion" back/>

    @if ($component = livewire_name('app/promotion/form'))
        @livewire($component, compact('promotion'))
    @endif
</div>