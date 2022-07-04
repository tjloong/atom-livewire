<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$promotion->name" back>
        <x-button.delete inverted
            title="Delete Promotion"
            message="Are you sure to delete this promotion?"
        />
    </x-page-header>

    @if ($component = livewire_name('app/promotion/form'))
        @livewire($component, compact('promotion'))
    @endif
</div>