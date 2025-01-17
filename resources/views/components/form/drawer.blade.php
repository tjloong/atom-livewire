<form wire:submit.prevent="submit">
    <x-modal.drawer {{ $attributes }}>
        <x-slot:buttons>
            @isset($buttons)
                @if ($buttons->isNotEmpty()) {{ $buttons }}
                @elseif (!$buttons->attributes->get('blank')) <x-button action="submit"/>
                @endif

                @if ($buttons->attributes->get('archive', false)) <x-button action="archive"/> @endif
                @if ($buttons->attributes->get('trash', false)) <x-button action="trash" invert no-label x-tooltip="{{ js(t('trash')) }}"/> @endif
                @if ($buttons->attributes->get('delete', false)) <x-button action="delete" invert no-label x-tooltip="{{ js(t('delete')) }}"/> @endif
                @if ($buttons->attributes->get('restore', false)) <x-button action="restore"/> @endif
            @else
                <x-button action="submit"/>
            @endisset
        </x-slot:buttons>

        @isset($heading)
            <x-slot:heading
                :title="$heading->attributes->get('title')"
                :subtitle="$heading->attributes->get('subtitle')"
                :status="$heading->attributes->get('status')">
                {{ $heading }}
            </x-slot:heading>
        @endisset

        {{ $slot }}
    </x-modal.drawer>
</form>