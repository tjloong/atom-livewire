<div class="max-w-screen-xl mx-auto w-full">
    @if ($fullpage)
        <x-page-header 
            :title="$this->title"
            :tinylink="$this->preferencesRoute && auth()->user()->can('preference.manage')
                ? ['label' => 'Preferences', 'href' => route('app.preferences', [str()->plural($type)])]
                : null"
        >
            @can($type.'.create')
                <x-button 
                    :label="str()->headline('New '.$type)" 
                    :href="route('app.document.create', [$type])"
                />
            @endcan
        </x-page-header>
    @endif

    <x-table :data="$this->paginator->items()">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>

        <x-slot:empty>
            <x-empty-state 
                :title="str()->headline('No '.str()->plural($type))"
                :subtitle="'The '.str($type)->headline()->lower().' list is empty.'"
            />
        </x-slot:empty>
    </x-table>

    {!! $this->paginator->links() !!}
</div>