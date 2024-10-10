@php
$utc = $attributes->get('utc', true);
$size = $attributes->get('size');
$time = $attributes->get('time');
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$variant = $attributes->get('variant', 'date');
$required = $attributes->get('required');
$placeholder = $attributes->get('placeholder', 'Select date');

$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;

$classes = $attributes->classes()
    ->add('w-full py-2 pl-3 pr-10 text-zinc-700 text-left')
    ->add('border border-zinc-200 border-b-zinc-300/80 rounded-lg shadow-sm bg-white')
    ->add('focus:outline-none focus:border-primary group-focus/input:border-primary hover:border-primary-300')
    ->add($size === 'sm' ? 'h-8 text-sm' : 'h-10')
    ->add('invalid:border-red-400 group-has-[[data-atom-error]]/field:border-red-400')
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

        <atom:_date-picker :attributes="$attributes->except(['label', 'caption'])"/>
        <atom:_error>@t($error)</atom:_error>
        <atom:caption>@t($caption)</atom:caption>
    </atom:_field>
@else
    <div
        wire:ignore
        x-data="datepicker({
            utc: {{ js($utc) }},
            time: {{ js($time) }},
            range: {{ js($variant === 'range') }},
            @if ($attributes->wire('model')->value())
            value: @entangle($attributes->wire('model')),
            @endif
        })"
        x-on:click.away="close()"
        x-on:keydown.down.prevent="!visible && open()"
        x-on:keydown.enter.prevent="visible ? close() : open()"
        x-on:keydown.space.prevent="visible ? close() : open()"
        x-on:keydown.esc.prevent="close()"
        class="group/datepicker relative w-full"
        data-atom-date-picker>
        <div class="relative block">
            <button
                type="button"
                x-ref="trigger"
                x-on:click="visible ? close() : open()"
                {{ $attrs }}>
                <template x-if="value" hidden>
                    <div
                        x-text="() => {
                            let sel = getSelected()
                            let format = config.time ? 'DD MMM YYYY hh:mm A' : 'DD MMM YYYY'

                            if (!sel || !sel.length) return ''

                            return config.range
                                ? `${sel[0].format(format)} - ${sel[1].format(format)}`
                                : sel[0].format(format)
                        }">
                    </div>
                </template>

                <template x-if="!value" hidden>
                    <div class="text-zinc-400 text-left">
                        {{ t($placeholder) }}
                    </div>
                </template>

                <div class="z-1 absolute top-0 bottom-0 flex items-center justify-center pr-3 right-0">
                    <div
                        x-show="value"
                        x-on:click.stop="clear()"
                        class="flex items-center justify-center w-full h-full">
                        <atom:icon close size="14"/>
                    </div>

                    <div class="pointer-events-none flex items-center justify-center w-full h-full text-zinc-400">
                        <atom:icon calendar x-show="!value"/>
                    </div>
                </div>
            </button>
        </div>

        <div
            x-ref="calendar"
            x-show="visible"
            class="absolute z-10 rounded-lg shadow-sm border border-zinc-200 bg-white opacity-0 transition-opacity duration-75 overflow-auto w-full md:w-auto">
            @if ($variant === 'range')
                <div class="flex items-center justify-center divide-x">
                    <div>
                        <div x-ref="from" class="w-[300px]"></div>

                        @if ($time)
                            <div class="border-t p-2">
                                <atom:_time-picker x-model="time[0]"/>
                            </div>
                        @endif
                    </div>

                    <div>
                        <div x-ref="to" class="w-[300px]"></div>

                        @if ($time)
                            <div class="border-t p-2">
                                <atom:_time-picker x-model="time[1]"/>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif ($time)
                <div class="border-t p-2">
                    <atom:_time-picker x-model="time[0]"/>
                </div>
            @endif
        </div>
    </div>
@endif
