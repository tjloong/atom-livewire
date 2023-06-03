@props([
    'min' => $attributes->get('min'),
    'max' => $attributes->get('max'),
    'step' => $attributes->get('step', 1),
    'model' => $attributes->wire('model')->value(),
])

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            focus: false,
            min: @js($min),
            max: @js($max),
            step: @js($step),
            value: @js(data_get($this, $model)),
            init () {
                this.$watch('value', (val) => this.$dispatch('input', val))
            },
            format (val) {
                this.value = isNaN(val) ? 1 : val

                if (!empty(this.min) && this.value < +this.min) this.value = +this.min 
                if (!empty(this.max) && this.value > +this.max) this.value = +this.max 
            },
            decrease () {
                if (empty(this.min) || this.value > this.min) this.value = this.value - (+this.step)
            },
            increase () {
                if (empty(this.max) || this.value < this.max) this.value = this.value + (+this.step)
            },
        }"
        x-on:click="$refs.input.focus()"
        x-bind:class="focus && 'active'"
        {{ $attributes->class([
            'form-input flex items-center gap-2',
            $attributes->get('class'),
        ]) }}
    >
        <div x-on:click="decrease" class="shrink-0 flex items-center justify-center cursor-pointer">
            <x-icon name="minus"/>
        </div>

        <input type="text"
            x-ref="input"
            x-model="value"
            x-on:focus="focus = true"
            x-on:blur="focus = false"
            x-on:input.stop="format($event.target.value)"
            class="form-input transparent text-center w-full grow"
        >

        <div x-on:click="increase" class="shrink-0 flex items-center justify-center cursor-pointer">
            <x-icon name="plus"/>
        </div>
    </div>
</x-form.field>