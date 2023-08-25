@props([
    'id' => component_id($attributes, 'form'),
    'submit' => $attributes->get('submit', 'submit'),
    'confirm' => $attributes->get('confirm'),
])

<form 
    x-data="{
        confirm: @js($confirm),
        submit () {
            if (this.confirm) {
                $dispatch('confirm', {
                    title: @js(__('atom::form.confirm.title')),
                    message: @js(__('atom::form.confirm.message')),
                    onConfirmed: () => $wire.call(@js($submit)),
                })
            }
            else $wire.call(@js($submit))
        },
    }"
    x-on:submit.prevent="submit">
    <x-box {{ $attributes->except('heading') }}>
        @isset($heading)
            <x-slot:heading
                :icon="$heading->attributes->get('icon')"
                :title="$heading->attributes->get('title')"
                :subtitle="$heading->attributes->get('subtitle')">
                {{ $heading }}
            </x-slot:heading>
        @endisset

        {{ $slot }}

        @isset($foot)
            @if ($foot->isNotEmpty())
                <x-slot:foot>
                    {{ $foot }}
                </x-slot:foot>
            @endif
        @else
            <x-slot:foot>
                <x-button.submit/>
            </x-slot:foot>
        @endif
    </x-box>
</form>
