<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title" back>
        <div class="flex items-center gap-2">
            @if($this->actions->get('edit'))
                @if ($master = $document->splittedFrom)
                    <x-button color="gray" label="Edit"
                        x-on:click="$dispatch('confirm', {
                            title: '{{ __('Edit Splitted Invoice') }}',
                            message: '{{ __('This invoice is splitted from #'.$master->number.'. Do you want to edit the master invoice?') }}',
                            onConfirmed: () => window.location = '{{ route('app.document.update', [$document->id]) }}',
                        })"
                    />
                @else
                    <x-button color="gray"
                        label="Edit"
                        :href="route('app.document.update', [$document->id])"
                    />
                @endif
            @endif
    
            <x-dropdown>
                <x-slot:anchor>
                    <x-button color="gray"
                        label="More"
                        :icon="['name' => 'chevron-down', 'position' => 'right']"
                    />
                </x-slot:anchor>

                @if ($this->actions->get('pdf'))
                    <x-dropdown.item icon="pdf" target="_blank"
                        label="View PDF"
                        wire:click="pdf"
                    />
                @endif

                @if ($this->actions->get('send'))
                    <x-dropdown.item icon="paper-plane"
                        label="Send Email" 
                        wire:click="openEmailModal"
                    />
                
                    <x-dropdown.item icon="share-from-square"
                        :label="$document->last_sent_at ? 'Unmark Sent' : 'Mark Sent'"
                        wire:click="toggleSent"
                    />
                @endif

                @if ($this->actions->get('share'))
                    <x-dropdown.item icon="share-nodes" class="cursor-pointer"
                        label="Share"
                        x-on:click="$dispatch('shareable-open')"
                    />
                @endif

                @if($this->actions->get('delete'))
                    <x-dropdown.delete
                        :title="str()->headline('Delete '.$document->type)"
                        message="Are you sure to delete this document?"
                    />
                @endif
            </x-dropdown>
        </div>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-8">
            @livewire(lw('app.document.view.info'), [
                'document' => $document,
            ], key('info'))
        </div>

        <div class="lg:col-span-4 flex flex-col gap-6">
            <x-box header="Information">
                <div class="grid divide-y text-sm">
                    <x-box.row label="Owner" class="py-2 px-3">{{ $document->ownedBy->name }}</x-box.row>
                    <x-box.row label="Created Date" class="py-2 px-3">{{ format_date($document->created_at, 'datetime') }}</x-box.row>

                    @if ($this->actions->get('send'))
                        <x-box.row label="Last Sent" class="py-2 px-3">{{ format_date($document->last_sent_at, 'datetime') ?? 'Never' }}</x-box.row>
                    @endif
                </div>
            </x-box>

            @if ($this->actions->get('invoice'))
                @livewire(lw('app.document.view.invoice'), [
                    'document' => $document,
                ], key('invoice'))
            @endif

            @if ($this->actions->get('bill'))
                @livewire(lw('app.document.view.bill'), [
                    'document' => $document,
                ], key('bill'))
            @endif

            @if ($this->actions->get('split'))
                @livewire(lw('app.document.view.split'), [
                    'document' => $document,
                ], key('split'))
            @endif

            @if ($this->actions->get('payment'))
                @livewire(lw('app.document.view.payment'), [
                    'document' => $document,
                ], key('payment'))
            @endif

            @livewire(lw('app.document.view.file'), [
                'document' => $document,
            ], key('files'))

            @if ($this->actions->get('share'))
                <x-shareable :shareable="$document->shareable"/>
            @endif

            @if ($this->actions->get('send'))
                @livewire(lw('app.document.view.email-form-modal'), [
                    'document' => $document,
                ], key('send-email'))
            @endif
        </div>
    </div>
</div>