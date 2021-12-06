<form wire:submit.prevent="save">
    <x-input.title wire:model.defer="page.title">
        Page Title
    </x-input.title>

    <div class="mb-6">
        <x-input.richtext wire:model.debounce.500ms="page.content">
            {!! $page->content !!}
        </x-input.richtext>
        <div class="text-xs text-gray-500 mt-2">
            @if ($autosavedAt)
                Auto saved at {{ format_date($autosavedAt, 'time-full') }}
            @endif
        </div>
    </div>
    
    <x-button type="submit" color="green" icon="check">
        Save Page
    </x-button>
</form>
