@php
    $min = $attributes->get('min');
    $max = $attributes->get('max');
    $step = $attributes->get('step', 1);
    $placeholder = $attributes->get('placeholder', 'Qty');
@endphp

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            wirevalue: @entangle($attributes->wire('model')),
            input: 1,
            focus: false,
            min: @js($min),
            max: @js($max),
            step: @js($step),
            validate (e) {
                if (
                    !['Home', 'End', 'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', '.', '-'].includes(e.key)
                    && isNaN(+e.key)
                ) {
                    e.preventDefault()
                }
            },
            up () {
                if (empty(this.max) || this.input < +this.max) this.input = +this.input + (+this.step)
            },
            down () {
                if (empty(this.min) || this.input > +this.min) this.input = +this.input - (+this.step)
            },
            format () {
                let val = isNaN(+this.input) ? 1 : this.input

                if (!empty(this.min) && val < +this.min) val = this.min 
                if (!empty(this.max) && val > +this.max) val = this.max 

                this.input = val
                this.$dispatch('input', this.input)
            },
        }"
        x-modelable="input"
        x-init="$nextTick(() => {
            if (!empty(wirevalue)) input = wirevalue
            $watch('input', () => format())
            $watch('wirevalue', wirevalue => input = wirevalue)
        })"
        x-on:click="focus = true"
        x-on:click.away="focus = false"
        x-bind:class="focus && 'active'"
        {{ $attributes
            ->merge(['class' => 'form-input flex items-center gap-2'])
            ->except(['min', 'max', 'step', 'placeholder']) }}>
        <button type="button" class="shrink-0 flex" x-on:click="down()">
            <x-icon name="minus" class="m-auto"/>
        </button>

        <div x-on:input.stop class="grow">
            <input type="text" class="transparent text-center w-full"
                x-ref="input"
                x-model="input"
                x-on:input.stop
                x-on:keydown="validate"
                placeholder="{{ tr($placeholder) }}">
        </div>

        <button type="button" class="shrink-0 flex" x-on:click="up()">
            <x-icon name="plus" class="m-auto"/>
        </button>
    </div>
</x-form.field>