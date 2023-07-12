@props([
    'id' => component_id($attributes, 'form'),
    'submit' => $attributes->get('submit', 'submit'),
    'confirm' => $attributes->get('confirm'),
])

<form 
    x-data="{
        confirm: @js($confirm),
        open () {
            const modal = $el.querySelector('#modal')
            const drawer = $el.querySelector('#drawer')
            
            if (modal) modal.dispatchEvent(new Event('open', { bubbles: true }))
            if (drawer) drawer.dispatchEvent(new Event('open', { bubbles:true }))
        },
        close () {
            const modal = $el.querySelector('#modal')
            const drawer = $el.querySelector('#drawer')
            
            if (modal) modal.dispatchEvent(new Event('close', { bubbles: true }))
            if (drawer) drawer.dispatchEvent(new Event('close', { bubbles:true }))
        },
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
    x-on:{{ $id }}-open.window="open"
    x-on:{{ $id }}-close.window="close"
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
    @elseif ($attributes->get('drawer'))
        <x-drawer {{ $attributes->except('submit', 'confirm') }}>
            @isset($header)
                <x-slot:header>{{ $header }}</x-slot:header>
            @endisset

            <x-slot:buttons>
                @isset($buttons) {{ $buttons }}
                @else <x-button.submit size="sm"/>
                @endisset
            </x-slot:buttons>

            <div class="px-2">
                {{ $slot }}
            </div>
        </x-drawer>
    @else
        <x-box :header="$attributes->get('header')">
            @isset($header)
                <x-slot:header>{{ $header }}</x-slot:header>
            @endisset

            @isset($buttons)
                <x-slot:buttons>{{ $buttons }}</x-slot:buttons>
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
    @endif
</form>
