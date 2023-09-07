<form wire:submit.prevent="submit">
    <x-drawer {{ $attributes }}>
        @isset($heading)
            <x-slot:heading>
                <x-heading
                    icon="{{ $heading->attributes->get('icon') }}"
                    title="{!! $heading->attributes->get('title') !!}"
                    subtitle="{!! $heading->attributes->get('subtitle') !!}">
                    {{ $heading }}
                </x-heading>
            </x-slot:heading>
        @endisset
    
        @isset($buttons)
            <x-slot:buttons
                :archive="$buttons->attributes->get('archive', false)"
                :trash="$buttons->attributes->get('trash', false)"
                :delete="$buttons->attributes->get('delete', false)"
                :restore="$buttons->attributes->get('restore', false)">
                @if ($buttons->isNotEmpty())
                    {{ $buttons }}
                @elseif (!$buttons->attributes->get('blank'))
                    <x-button.submit size="sm"/>
                @endif
            </x-slot:buttons>
        @else
            <x-slot:buttons>
                <x-button.submit size="sm"/>
            </x-slot:buttons>
        @endif
    
        @isset($dropdown)
            <x-slot:dropdown
                :trash="$dropdown->attributes->get('trash', false)"
                :delete="$dropdown->attributes->get('delete', false)"
                :restore="$dropdown->attributes->get('restore', false)">
                {{ $dropdown }}
            </x-slot:dropdown>
        @endisset

        {{ $slot }}
    </x-drawer>
</form>