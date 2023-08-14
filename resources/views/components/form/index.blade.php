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
    id="{{ $id }}"
    {{ $attributes->except([
        'id', 
        'submit', 
        'confirm', 
        'status', 
        'title', 
        'subtitle',
        'header', 
        'modal', 
        'drawer',
        'size',
        'show',
        'bg-close',
    ]) }}
>
    @if ($attributes->get('modal'))
        <x-modal :id="$id" 
            :header="$attributes->get('header')" 
            :size="$attributes->get('size')"
            :show="$attributes->get('show')"
            :bg-close="$attributes->get('bg-close')"
        >
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
        <x-drawer :id="$id" 
            :header="$attributes->get('header')"
            :title="$attributes->get('title')"
            :subtitle="$attributes->get('subtitle')"
            :size="$attributes->get('size')"
            :show="$attributes->get('show')"
            :status="$attributes->get('status')"
            :bg-close="$attributes->get('bg-close')"
        >
            @isset($header)
                <x-slot:header>{{ $header }}</x-slot:header>
            @endisset

            @isset($buttons)
                <x-slot:buttons
                    :trash="$buttons->attributes->get('trash', false)"
                    :delete="$buttons->attributes->get('delete', false)"
                    :restore="$buttons->attributes->get('restore', false)">
                    <x-button.submit size="sm"/>
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
