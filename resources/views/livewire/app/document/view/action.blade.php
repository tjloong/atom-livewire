<div class="flex items-center gap-2">
    @can($document->type.'.update')
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
    @endcan

    <x-dropdown>
        <x-slot:anchor>
            <x-button color="gray"
                label="More"
                :icon="['name' => 'chevron-down', 'position' => 'right']"
            />
        </x-slot:anchor>

        <x-dropdown.item icon="pdf" target="_blank"
            label="View PDF"
            wire:click="pdf"
        />
        
        @can($document->type.'.update')
            <x-dropdown.item icon="paper-plane"
                label="Send Email" 
                wire:click="$emitTo('{{ lw('app.document.view.email-modal') }}', 'open')"
            />
            
            <x-dropdown.item icon="share-from-square"
                :label="$document->last_sent_at ? 'Unmark Sent' : 'Mark Sent'"
                wire:click="toggleSent"
            />

            <x-dropdown.item icon="share-nodes" class="cursor-pointer"
                label="Share"
                x-on:click="$dispatch('shareable-open')"
            />
        @endcan
        
        @can($document->type.'.delete')
            <x-dropdown.delete
                :title="str()->headline('Delete '.$document->type)"
                message="Are you sure to delete this document?"
            />
        @endcan
    </x-dropdown>
</div>