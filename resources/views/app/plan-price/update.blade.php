<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$planPrice->name" back>
        <x-button.delete inverted
            title="Delete Plan Price"
            message="Are you sure to delete this price?"
        />
    </x-page-header>

    @livewire('atom.plan-price.form', compact('plan', 'planPrice'))
</div>