<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$tax->label" back>
        <x-button.delete inverted can="tax.manage"
            title="Delete Tax"
            message="This will DELETE the tax. Are you sure?"
        />
    </x-page-header>

    @livewire(atom_lw('app.tax.form'), compact('tax'))
</div>