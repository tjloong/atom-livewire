@php
    $min = $attributes->get('min');
    $max = $attributes->get('max');
    $step = $attributes->get('step', 1);
    $placeholder = $attributes->get('placeholder', 'Qty');
@endphp

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            qty: @entangle($attributes->wire('model')),
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
                if (empty(this.max) || this.qty < +this.max) this.qty = +this.qty + (+this.step)
            },
            down () {
                if (empty(this.min) || this.qty > +this.min) this.qty = +this.qty - (+this.step)
            },
            format (val) {
                val = isNaN(+val) ? 1 : val

                if (!empty(this.min) && val < +this.min) val = this.min 
                if (!empty(this.max) && val > +this.max) val = this.max 

                this.qty = val
            },
        }"
        x-init="$watch('qty', qty => $dispatch('input', qty))"
        x-modelable="qty"
        x-on:click="focus = true"
        x-on:click.away="focus = false"
        x-bind:class="focus && 'active'"
        {{ $attributes
            ->merge(['class' => 'form-input flex items-center gap-2'])
            ->except(['min', 'max', 'step', 'placeholder']) }}>
        <button type="button" class="shrink-0 flex" x-on:click="down()">
            <x-icon name="minus" class="m-auto"/>
        </button>

        <input type="text" class="grow transparent text-center w-full"
            x-ref="input"
            x-model="qty"
            x-on:keydown="validate"
            x-on:input.stop="format($event.target.value)"
            placeholder="{{ tr($placeholder) }}">

        <button type="button" class="shrink-0 flex" x-on:click="up()">
            <x-icon name="plus" class="m-auto"/>
        </button>
    </div>
</x-form.field>