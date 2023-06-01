<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$plan->name" back>
        <x-button.delete inverted
            title="Delete Plan"
            message="Are you sure to DELETE this plan?"
        />
    </x-page-header>

    @livewire(atom_lw('app.plan.form'), compact('plan'), key('form'))
</div>