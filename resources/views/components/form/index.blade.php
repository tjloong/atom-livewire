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
    {{ $attributes->except('id', 'submit', 'confirm') }}
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
            :size="$attributes->get('size')"
            :show="$attributes->get('show')"
            :bg-close="$attributes->get('bg-close')"
        >
            @isset($header)
                <x-slot:header>{{ $header }}</x-slot:header>
            @endisset

            <x-slot:buttons>
                @isset($buttons) 
                    @if ($buttons->isNotEmpty()) 
                        {{ $buttons }}
                    @else
                        <div class="flex items-center gap-2">
                            @if ($buttons->attributes->get('restore'))
                                <x-button size="sm" icon="restore"
                                    label="Restore"
                                    wire:click="restore({{ json_encode($buttons->attributes->get('restore')) }})"/>
                            @else
                                <x-button.submit size="sm"/>
                            @endif

                            @if ($buttons->attributes->get('trash'))
                                <x-button.delete size="sm" inverted
                                    label="Trash"
                                    title="Move to Trash"
                                    message="Are you sure to move this record to trash? You can restore it later."
                                    callback="trash"
                                    :params="$buttons->attributes->get('trash')"/>
                            @endif

                            @if ($buttons->attributes->get('delete'))
                                <x-button.delete size="sm" inverted
                                    title="Permanently Delete Record"
                                    message="Are you sure to DELETE this record? This action CANNOT BE UNDONE."
                                    :params="$buttons->attributes->get('delete')"/>
                            @endif
                        </div>
                    @endif
                @else 
                    <x-button.submit size="sm"/>
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
