<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$plan->name" back>
        <x-button.delete inverted
            title="Delete Plan"
            message="Are you sure to DELETE this plan?"
        />
    </x-page-header>

    <div class="flex flex-col gap-6">
        @livewire(lw('app.plan.form'), compact('plan'), key('form'))
        @livewire(lw('app.plan.price.listing'), compact('plan'), key('price'))
    </div>
</div>