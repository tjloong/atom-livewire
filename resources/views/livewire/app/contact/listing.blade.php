<div class="max-w-scree-xl mx-auto">
    <x-page-header 
        :title="str()->title(str()->plural($type))"
        :tinylink="$this->preferencesRoute && auth()->user()->can('preference.manage')
            ? ['label' => 'Preferences', 'href' => $this->preferencesRoute]
            : null"
    >
        @can('contact.create')
            <x-button
                :label="'New '.str()->title($type)"
                :href="route('app.contact.create', [$type])"
            />
        @endcan
    </x-page-header>

    <x-table :data="$this->contacts->items()">
        <x-slot:header>
            <x-table.searchbar :total="$this->contacts->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->contacts->links() !!}
</div>