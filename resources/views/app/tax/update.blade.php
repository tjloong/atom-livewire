<div class="max-w-lg mx-auto">
    <x-page-header :title="$tax->name" :subtitle="collect([$tax->country, $tax->region])->filter()->join(', ')" back>
        <x-button.delete inverted 
            title="Delete Plan"
            message="Are you sure to delete this plan?"
        />
    </x-page-header>

    @livewire('atom.app.tax.form', compact('tax'))
</div>