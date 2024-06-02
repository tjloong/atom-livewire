@php
$for = $attributes->get('for') ?? $attributes->wire('model')->value();
$size = $attributes->get('size');
$icon = $attributes->get('icon');
$type = $attributes->get('type');
$label = $attributes->get('label');
$readonly = $attributes->get('readonly');
$disabled = $attributes->get('disabled');
$nolabel = $attributes->get('no-label');
$transparent = $attributes->get('transparent');
$wiremodel = $attributes->wire('model')->value();

$placeholder = $attributes->get('placeholder') ?? pick([
    'app.label.qty' => in_array($type, ['qty', 'quantity']),
]);

$size = $attributes->get('size') ?? pick([
    '2xs' => $attributes->get('2xs'),
    'xs' => $attributes->get('xs'),
    'sm' => $attributes->get('sm'),
    'lg' => $attributes->get('lg'),
    'xl' => $attributes->get('xl'),
    '2xl' => $attributes->get('2xl'),
    'md' => true,
]);

$size = [
    '2xs' => 'text-[9px] h-5',
    'xs' => 'text-xs h-6',
    'sm' => 'text-sm h-8',
    'md' => 'text-base h-10',
    'lg' => 'text-lg font-medium h-12',
    'xl' => 'text-xl font-semibold h-[4rem]',
    '2xl' => 'text-2xl font-semibold h-[4rem]',
][$size];

$except = [
    'for', 'size', 'icon', 'type', 'label', 'no-label', 'transparent', 'placeholder', 'min', 'max', 'step',
    '2xs', 'xs', 'sm', 'md', 'lg', 'xl', '2xl', 'readonly', 'disabled',
];
@endphp

<div>
    @if (!$nolabel)
        <div class="mb-2">
            <x-label :label="$label" :for="$for"/>
        </div>
    @endif

    <span {{ $attributes->class(array_filter([
        'inline-block leading-normal',
        $transparent
            ? 'bg-transparent has-[:focus]:border-b-2 has-[:focus]:border-gray-300 has-[:focus]:border-dashed'
            : 'px-2 bg-white border border-gray-300 rounded-md has-[:focus]:ring-1 has-[:focus]:ring-theme has-[:focus]:ring-offset-1 hover:ring-1 hover:ring-gray-200',
        $disabled ? 'opacity-50' : null,
        $size,
        $attributes->get('class'),
    ]))->only('class') }}>
        @if (in_array($type, ['qty', 'quantity']))
            <span
                x-data="{
                    value: @if ($wiremodel) @entangle($wiremodel) @else null @endif,
                    readonly: @js($readonly),

                    get min () {
                        return +this.$refs.number.getAttribute('min')
                    },

                    get max () {
                        return +this.$refs.number.getAttribute('max')
                    },

                    get step () {
                        return +this.$refs.number.getAttribute('step')
                    },

                    init () {
                        this.$nextTick(() => this.validate())
                        this.$watch('value', (value, old) => { if (value !== old) this.validate() })
                    },

                    spin (action) {
                        if (action === 'up') this.$refs.number.stepUp()
                        if (action === 'down') this.$refs.number.stepDown()
                        this.$refs.number.focus()
                        this.$refs.number.select()
                        this.$refs.number.dispatchEvent(new Event('input'))
                    },

                    validate () {
                        if (this.value === null) {
                            this.value = this.min
                        }
                        else {
                            this.value = +this.value
                            this.value = this.value.round(this.step.decimalPlaces())
                            if (this.value > this.max) this.value = this.max
                            if (this.value < this.min) this.value = this.min
                        }
                    },
                }"
                x-modelable="value"
                class="inline-flex items-center h-full"
                {{ $attributes->whereStartsWith('x-model') }}>
                @if (!$readonly && !$disabled)
                    <button type="button" class="flex py-1.5 px-3" x-on:click.stop="spin('down')">
                        <x-icon name="minus" class="m-auto"/>
                    </button>
                @endif

                <input type="number" x-ref="number" x-model{{ $attributes->modifier() }}="value"
                    class="grow appearance-none bg-transparent min-w-[4rem] text-center no-spinner"
                    placeholder="{!! tr($placeholder) !!}"
                    @readonly($readonly)
                    @disabled($disabled)
                    {{ $attributes->merge([
                        'min' => 1,
                        'max' => 9999,
                        'step' => 1,
                    ])->only(['min', 'max', 'step']) }}>

                @if (!$readonly && !$disabled)
                    <button type="button" class="flex py-1.5 px-3" x-on:click.stop="spin('up')">
                        <x-icon name="plus" class="m-auto"/>
                    </button>
                @endif
            </span>
        @endif
    </span>
</div>
