@php
$utc = $attributes->get('utc', true);
$size = $attributes->get('size');
$time = $attributes->get('time');
$label = $attributes->get('label');
$inline = $attributes->get('inline');
$caption = $attributes->get('caption');
$variant = $attributes->get('variant', 'date');
$placeholder = $attributes->get('placeholder', pick([
    'select-date-time' => $time,
    'select-date-range' => $variant === 'range',
    'select-date' => true,
]));

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
    <atom:_input.field
        :label="$label"
        :caption="$caption"
        :inline="$inline"
        :required="$required"
        :error="$error">
        <atom:_date-picker :attributes="$attributes->except(['label', 'caption', 'error', 'inline'])"/>
    </atom:_input.field>
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
        x-on:keydown.down.prevent="open()"
        x-on:keydown.enter.prevent="open()"
        x-on:keydown.space.prevent="open()"
        x-on:keydown.esc.prevent="close()"
        class="group/datepicker relative w-full"
        data-atom-date-picker>
        <div data-anchor class="relative block">
            <button
                type="button"
                x-on:click="open()"
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
                        }"
                        class="truncate">
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

        <atom:popover>
            <atom:menu>
                @if ($variant === 'range')
                    <div class="md:flex md:divide-x">
                        <div class="md:w-40 md:pr-1">
                            <atom:menu-item x-on:click="selectCustomRange(dayjs().startOf('day'), dayjs().endOf('day'))">@t('today')</atom:menu-item>
                            <atom:menu-item x-on:click="selectCustomRange(dayjs().subtract(1, 'day').startOf('day'), dayjs().subtract(1, 'day').endOf('day'))">@t('yesterday')</atom:menu-item>
                            <atom:menu-item x-on:click="selectCustomRange(dayjs().startOf('month').startOf('day'), dayjs().endOf('month').endOf('day'))">@t('this-month')</atom:menu-item>
                            <atom:menu-item x-on:click="selectCustomRange(dayjs().startOf('year').startOf('day'), dayjs().endOf('year').endOf('day'))">@t('this-year')</atom:menu-item>
                            <atom:menu-item x-on:click="selectCustomRange(dayjs().subtract(6, 'day').startOf('day'), dayjs().endOf('day'))">@t('last-7-days')</atom:menu-item>
                            <atom:menu-item x-on:click="selectCustomRange(dayjs().subtract(29, 'day').startOf('day'), dayjs().endOf('day'))">@t('last-30-days')</atom:menu-item>
                            <atom:menu-item x-on:click="selectCustomRange(dayjs().startOf('month').subtract(1, 'day').startOf('month').startOf('day'), dayjs().startOf('month').subtract(1, 'day').endOf('month').endOf('day'))">@t('last-month')</atom:menu-item>
                            <atom:menu-item x-on:click="selectCustomRange(dayjs().startOf('year').subtract(1, 'day').startOf('year').startOf('day'), dayjs().startOf('year').subtract(1, 'day').endOf('year').endOf('day'))">@t('last-year')</atom:menu-item>
                        </div>

                        <atom:separator class="mt-2 md:hidden">@t('custom-date-range')</atom:separator>

                        <div class="divide-y md:flex md:divide-x md:divide-y-0">
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
                    </div>
                @elseif ($time)
                    <div x-ref="calendar"></div>
                    <div class="border-t p-2">
                        <atom:_time-picker x-model="time[0]"/>
                    </div>
                @else
                    <div x-ref="calendar"></div>
                @endif
            </atom:menu>
        </atom:popover>
    </div>
@endif
