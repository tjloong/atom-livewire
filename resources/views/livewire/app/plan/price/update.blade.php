<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$price->name" back>
        <x-button.delete inverted
            title="Delete Plan Price"
            message="Are you sure to delete this price?"
        />
    </x-page-header>

    @livewire(lw('app.plan.price.form'), compact('plan', 'price'))
</div>