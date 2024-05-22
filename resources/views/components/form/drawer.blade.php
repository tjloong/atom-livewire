<form wire:submit.prevent="submit">
    <x-modal.drawer {{ $attributes }}>
        @isset($heading)
            <x-slot:heading>
                @if ($heading->attributes->get('title'))
                    <x-heading
                        icon="{{ $heading->attributes->get('icon') }}"
                        title="{!! $heading->attributes->get('title') !!}"
                        subtitle="{!! $heading->attributes->get('subtitle') !!}"
                        :status="$heading->attributes->get('status')">
                        {{ $heading }}
                    </x-heading>
                @else
                    {{ $heading }}
                @endif
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
                    <x-button.submit sm/>
                @endif
            </x-slot:buttons>
        @else
            <x-slot:buttons>
                <x-button.submit sm/>
            </x-slot:buttons>
        @endif
    
        @isset($dropdown)
            <x-slot:dropdown
                :archive="$dropdown->attributes->get('archive', false)"
                :trash="$dropdown->attributes->get('trash', false)"
                :delete="$dropdown->attributes->get('delete', false)"
                :restore="$dropdown->attributes->get('restore', false)">
                {{ $dropdown }}
            </x-slot:dropdown>
        @endisset

        {{ $slot }}
    </x-modal.drawer>
</form>