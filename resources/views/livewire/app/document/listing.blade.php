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

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->documents->total()"/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Date" sort="issued_at"/>
            <x-table.th label="Number" sort="number"/>

            @if (!$contact)
                <x-table.th :label="in_array($type, ['purchase-order', 'bill']) ? 'Vendor' : 'Client'" sort="contacts.name"/>
            @endif

            @if ($type !== 'delivery-order')
                <x-table.th label="Amount" sort="grand_total" class="text-right"/>
            @endif

            <x-table.th class="text-right"/>
            <x-table.th label="Owner" sort="users.name" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->documents as $document)
            <x-table.tr>
                <x-table.td :date="$document->issued_at"/>

                <x-table.td
                    :label="$document->number"
                    :href="route('app.document.view', [$document->id])"
                />

                @if (!$contact)
                    <x-table.td
                        :label="$document->contact->name"
                        :href="route('app.document.view', [$document->id])"
                        :small="str()->limit($document->summary, 100)"
                    />
                @endif

                @if ($type !== 'delivery-order')
                    <x-table.td>
                        <div class="flex flex-col items-end">
                            @if ($document->splittedFrom || $document->splits()->count())
                                <div class="flex items-center gap-2">
                                    <div class="text-sm text-gray-500">({{ __('splitted') }})</div>
                                    {{ currency($document->splitted_total, $document->currency) }}
                                </div>
                            @else
                                {{ currency($document->grand_total, $document->currency) }}
                            @endif

                            @if ($converted = $document->getConvertedTotal('grand_total'))
                                <div class="text-sm text-gray-500 font-medium">
                                    {{ currency($converted, default_currency()) }}
                                </div>
                            @endif
                        </div>
                    </x-table.td>
                @endif

                <x-table.td :status="$document->status" class="text-right"/>
                <x-table.td :label="optional($document->ownedBy)->name" class="text-right"/>
            </x-table.tr>
        @endforeach

        <x-slot:empty>
            <x-empty-state 
                :title="str()->headline('No '.str()->plural($type))"
                :subtitle="'The '.str($type)->headline()->lower().' list is empty.'"
            >
                <x-button color="gray"
                    :label="str()->headline('New '.$type)"
                    :href="route('app.document.create', [
                        'type' => $type,
                        'contact' => optional($contact)->id,
                    ])"
                />
            </x-empty-state>
        </x-slot:empty>
    </x-table>

    {!! $this->documents->links() !!}
</div>