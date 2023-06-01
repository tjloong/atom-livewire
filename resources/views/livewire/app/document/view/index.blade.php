<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title" back>
        <div class="flex items-center gap-2">
            @can($document->type.'.update')
                @if ($master = $document->splittedFrom)
                    <x-button.confirm color="gray" label="Edit"
                        title="Edit Splitted Invoice"
                        :message="'This invoice is splitted from #'.$master->number.'. Do you want to edit the master invoice?'"
                        :href="route('app.document.update', [$document->id])"
                    />
                @else
                    <x-button color="gray" label="Edit" :href="route('app.document.update', [$document->id])"/>
                @endif
            @endcan
        
            <x-dropdown>
                <x-slot:anchor>
                    <x-button color="gray" label="More" icon="postfix:chevron-down"/>
                </x-slot:anchor>
        
                <x-dropdown.item wire:click="pdf" label="View PDF" icon="pdf" target="_blank"/>
                
                @can($document->type.'.update')
                    <x-dropdown.item wire:click="$emitTo('{{ atom_lw('app.document.view.email-modal') }}', 'open')" label="Send Email" icon="paper-plane"/>
                    <x-dropdown.item wire:click="toggleSent" :label="$document->last_sent_at ? 'Unmark Sent' : 'Mark Sent'" icon="share-from-square"/>
                    <x-shareable header="Share Document">
                        <x-dropdown.item label="Share" icon="share"/>
                    </x-shareable>
                @endcan
                
                @can($document->type.'.delete')
                    <x-dropdown.delete
                        :title="str()->headline('Delete '.$document->type)"
                        message="Are you sure to delete this document?"
                    />
                @endcan
            </x-dropdown>
        </div>
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-8/12">
            @livewire(atom_lw('app.document.view.body'), compact('document'), key('body'))
        </div>

        <div class="md:w-4/12">
            <div class="flex flex-col gap-6">
                <x-box header="Information">
                    <div class="flex flex-col divide-y">
                        @foreach ($this->additionalInfoFields as $key => $val)
                            <x-field :label="$key"
                                :value="is_string($val) ? $val : data_get($val, 'value')"
                                :href="data_get($val, 'href')"
                                :badge="data_get($val, 'badge')"
                                :tags="data_get($val, 'tags')"
                            />
                        @endforeach
                    </div>
                </x-box>
                
                @if ($document->convertedTo()->count())
                    @livewire(atom_lw('app.document.view.converted'), compact('document'), key('converted'))
                @endif

                @if (
                    $document->type === 'invoice'
                    && ($document->splits()->count() || !in_array($document->status, ['paid', 'partial']))
                )
                    @livewire(atom_lw('app.document.view.split'), compact('document'), key('split'))
                @endif

                @if (in_array($document->type, ['invoice', 'bill']))
                    @livewire(atom_lw('app.document.view.payment'), compact('document'), key('payment'))
                @endif

                @livewire(atom_lw('app.document.view.attachment'), compact('document'), key('attachment'))
            </div>
        </div>
    </div>

    @livewire(atom_lw('app.document.view.email-modal'), compact('document'), key('email-modal'))
</div>