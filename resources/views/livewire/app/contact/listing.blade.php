<div class="max-w-screen-xl mx-auto">
    <x-page-header 
        :title="$this->title"
        :tinylink="$this->preferencesRoute ? ['label' => 'Preferences', 'href' => $this->preferencesRoute] : null"
    >
        @can('contact.create')
            <x-button :label="'New '.str($this->title)->singular()"
                :href="route('app.contact.create', [$category])"
            />
        @endcan
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>