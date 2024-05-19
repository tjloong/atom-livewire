@php
$label = $attributes->get('label', 'app.label.label');
$placeholder = $attributes->get('placeholder', 'app.label.select-label');
$type = $attributes->get('type');
$parent = $attributes->get('parent');
$disabled = $attributes->get('disabled');
$params = compact('type', 'parent');
$except = ['label', 'disabled', 'placeholder', 'type', 'children', 'parent'];
@endphp

<x-form.select callback="labels" :label="$label" :placeholder="$placeholder" :params="$params" {{ $attributes->except($except) }}>
    <div class="w-full">
        <template x-if="multiple">
            <div class="flex items-center gap-2 flex-wrap">
                <template x-for="item in selection">
                    <div class="flex items-center rounded-md text-sm" x-bind:style="{
                        backgroundColor: item.color_value_inverted,
                        color: item.color_value,
                        border: `1px solid ${item.color_value}`,
                    }">
                        <div class="grid font-medium px-2">
                            <div x-text="item.label" class="truncate"></div>
                        </div>

                        <template x-if="show">
                            <div class="shrink-0 pr-2" x-on:click="remove(item.value)">
                                <x-icon name="xmark"/>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!multiple">
            <input type="text" placeholder="{{ tr($placeholder) }}" class="transparent grow w-full cursor-pointer" readonly>
        </template>
    </div>

    <x-slot:option>
        <div
            x-bind:style="{ color: opt.color_value }"
            x-bind:class="opt.color_value && 'font-medium'"
            class="py-2 px-4 flex items-center gap-2">
            <x-icon name="tag"/>
            <div x-text="opt.label"></div>
        </div>
    </x-slot:option>

    @isset($foot) 
        <x-slot:foot>{{ $foot }}</x-slot:foot>
    @else
        <x-slot:foot>
            <x-button sm label="app.label.new" icon="add" 
                x-on:click="Livewire.emit('createLabel', {{Js::from(['type' => $type])}})"/>
        </x-slot:foot>
    @endisset
</x-form.select>