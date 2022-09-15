<div class="max-w-lg mx-auto">
    <x-page-header :title="$tax->name" :subtitle="collect([$tax->country, $tax->region])->filter()->join(', ')" back>
        <x-button.delete inverted 
            title="Delete Tax"
            message="Are you sure to delete this tax?"
        />
    </x-page-header>

    @livewire(lw('app.tax.form'), compact('tax'))
</div>