<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Dashboard">
        <x-form.date-range wire:model="date"/>
    </x-page-header>
    
    <div class="grid gap-6 md:grid-cols-4">
        @if ($this->blogs)
            <x-statbox 
                title="Total Articles"
                :count="data_get($this->blogs, 'count')"
            />

            <x-statbox 
                title="Total Published"
                :count="data_get($this->blogs, 'published')"
            />
        @endif

        @if ($this->enquiries)
            <x-statbox 
                title="Total Enquiries"
                :count="data_get($this->enquiries, 'count')"
            />

            <x-statbox 
                title="Pending Enquiries"
                :count="data_get($this->enquiries, 'pending')"
            />
        @endif
    </div>
</div>