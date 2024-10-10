@php
$size = $attributes->get('size');
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$required = $attributes->get('required');

$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;

$classes = $attributes->classes()
    ->add('w-full py-2 px-3 text-zinc-700 flex items-center gap-2')
    ->add('border border-zinc-200 border-b-zinc-300/80 rounded-lg shadow-sm bg-white')
    ->add('has-[:focus]:border-primary hover:border-primary-300')
    ->add($size === 'sm' ? 'h-8 text-sm' : 'h-10')
    ->add('invalid:border-red-400 group-has-[[data-atom-error]]/field:border-red-400')
    ->add('group-[]/datepicker:border-0 group-[]/datepicker:shadow-none')
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['size'])
    ;
@endphp

@if ($label || $caption)
    <atom:_field>
        @if ($label)
            <atom:_label>
                <div class="inline-flex items-center justify-center gap-2">
                    @t($label)
                    @if ($required)
                        <atom:icon asterisk size="12" class="text-red-500 shrink-0"/>
                    @endif
                </div>
            </atom:_label>
        @endif

        <atom:_time-picker :attributes="$attributes->except(['label', 'caption'])"/>
        <atom:_error>@t($error)</atom:_error>
        <atom:caption>@t($caption)</atom:caption>
    </atom:_field>
@else
    <div
        x-data="timepicker({
            @if ($attributes->wire('model')->value())
            value: @entangle($attributes->wire('model')),
            @endif
        })"
        x-modelable="value"
        {{ $attrs }}
        data-atom-time-picker>
        <div x-on:input.stop class="grow flex items-center gap-2">
            <input type="number" x-model.lazy="hr" maxlength="2" class="appearance-none w-8 text-center no-spinner focus:outline-none">
            <span class="font-bold">:</span>
            <input type="number" x-model.lazy="min" maxlength="2" class="appearance-none w-8 text-center no-spinner focus:outline-none">
            <input type="text"
                x-bind:value="am"
                x-on:input="setAm()"
                x-on:keydown.up.prevent.stop="setAm()"
                x-on:keydown.down.prevent.stop="setAm()"
                x-on:keydown.enter.prevent.stop="setAm()"
                x-on:keydown.space.prevent.stop="setAm()"
                class="appearance-none w-8 text-center focus:outline-none">
        </div>

        <atom:icon time class="shrink-0 text-zinc-400 group-[]/datepicker:order-first"/>
    </div>
@endif
