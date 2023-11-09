@php
    $min = $attributes->get('min');
    $max = $attributes->get('max');
    $currency = $attributes->get('currency');
    $placeholder = $attributes->get('placeholder', 'Amount');
@endphp

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            amount: @entangle($attributes->wire('model')),
            focus: false,
            min: @js($min),
            max: @js($max),
            get formatted () {
                return this.amount?.toLocaleString()
            },
            validate (e) {
                if (
                    !['Home', 'End', 'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', '.', '-'].includes(e.key)
                    && isNaN(+e.key)
                ) {
                    e.preventDefault()
                }
            },
            format (val) {
                if (empty(val)) this.amount = null
                else {
                    val = val.replace(/,/g, '')

                    if (!empty(this.min) && +val < +this.min) val = +this.min 
                    if (!empty(this.max) && +val > +this.max) val = +this.max 
    
                    this.amount = isNaN(+val) ? null : +val
                }
            },
        }"
        x-modelable="amount"
        x-on:click="focus = true"
        x-on:click.away="focus = false"
        x-init="$watch('amount', amount => $dispatch('input', amount))"
        x-bind:class="focus && 'active'"
        class="form-input flex items-center gap-2"
        {{ $attributes->except(['class', 'min', 'max', 'currency', 'placeholder']) }}>
        {{-- // TODO amount currency --}}
        @if (is_array($currency))
        @elseif ($currency)
        @endif

        <input type="text" class="grow transparent w-full {{ $attributes->get('class') }}"
            x-ref="input"
            x-bind:value="formatted"
            x-on:keydown="validate"
            x-on:input.stop="format($event.target.value)"
            placeholder="{{ tr($placeholder) }}">
    </div>
</x-form.field>
