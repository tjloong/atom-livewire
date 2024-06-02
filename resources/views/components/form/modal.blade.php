<form wire:submit.prevent="submit">
    <x-modal :id="$id" 
        :size="$attributes->get('size')"
        :show="$attributes->get('show')"
        :bg-close="$attributes->get('bg-close')">
        @isset($heading)
            <x-slot:heading
                :icon="$heading->attributes->get('icon')"
                :title="$heading->attributes->get('title')"
                :subtitle="$heading->attributes->get('subtitle')">
                {{ $heading }}
            </x-slot:heading>
        @endisset

        {{ $slot }}

        <x-slot:foot>
            @isset($foot)
                {{ $foot }}
            @else
                <x-button action="submit"/>
            @endif
        </x-slot:foot>
    </x-modal>
</form>