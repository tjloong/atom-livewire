<div class="max-w-screen-xl mx-auto">
    @if ($fullpage)
        <x-page-header 
            :title="$this->title"
            :tinylink="$this->preferencesRoute ? ['label' => 'Preferences', 'href' => $this->preferencesRoute] : null"
        >
            @if (user()->can('contact.create') || user()->can($category.'.create'))
                <x-button :label="'New '.str($this->title)->singular()"
                    :href="route('app.contact.create', [$category])"
                />
            @endif
        </x-page-header>
    @endif

    <x-table :data="$this->table">
        <x-slot:header>
            @if (!$fullpage)
                <x-table.header :label="$this->title">
                    @if (user()->can('contact.create') || user()->can($category.'.create'))
                        <x-button color="gray" size="sm" :label="'New '.str($this->title)->singular()"
                            :href="route('app.contact.create', [$category])"
                        />
                    @endif
                </x-table.header>
            @endif
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>