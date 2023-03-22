<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$price->name" back>
        <x-button.delete inverted
            title="Delete Plan Price"
            message="Are you sure to DELETE this price?"
        />
    </x-page-header>

    @livewire(lw('app.plan.price.form'), compact('plan', 'price'))
</div>