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
                    title: @js(__(data_get($confirm, 'title', 'Submit Form'))),
                    message: @js(__(data_get($confirm, 'message', 'Are you sure to submit this form?'))),
                    onConfirmed: () => $wire.call(@js($submit)),
                })
            }
            else $wire.call(@js($submit))
        }
    }"
    x-on:submit.prevent="submit"
    x-on:{{ $id }}-open.window="$el.querySelector('#modal').dispatchEvent(new Event('open', { bubbles: true }))"
    x-on:{{ $id }}-close.window="$el.querySelector('#modal').dispatchEvent(new Event('close', { bubbles: true }))"
    id="{{ $id }}"
>
    @if ($attributes->get('modal'))
        <x-modal :header="$attributes->get('header')" :size="$attributes->get('size')">
            @isset($header)
                <x-slot:header>{{ $header }}</x-slot:header>
            @endisset

            {{ $slot }}

            <x-slot:foot>
                @isset($foot)
                    {{ $foot }}
                @else
                    <x-button.submit/>
                @endif
            </x-slot:foot>
        </x-modal>
    @else
        <x-box :header="$attributes->get('header')">
            @isset($header)
                <x-slot:header>{{ $header }}</x-slot:header>
            @endisset

            @isset($buttons)
                <x-slot:buttons>{{ $buttons }}</x-slot:buttons>
            @endisset

            {{ $slot }}

            <x-slot:foot>
                @isset($foot)
                    {{ $foot }}
                @else
                    <x-button.submit/>
                @endif
            </x-slot:foot>
        </x-box>
    @endif
</form>
