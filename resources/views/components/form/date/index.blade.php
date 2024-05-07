@php
    $placeholder = $attributes->get('placeholder', 'app.label.select-date');
@endphp

<x-form.field {{ $attributes }}>
    <div 
        wire:ignore
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            show: false,
            calendar: null,

            open () {
                this.show = true
                this.$nextTick(() => {
                    this.calendar = flatpickr(this.$refs.calendar, {
                        inline: true,
                        dateFormat: 'Y-m-d',
                        defaultDate: this.value,
                        onChange: (date, str) => {
                            this.value = str
                            this.close()
                        },
                    })
                })
            },

            close () {
                this.calendar?.destroy()
                this.calendar = null
                this.show = false
            },
        }"
        x-modelable="value"
        x-on:click.away="close()"
        x-on:keydown.esc="close()"
        {{ $attributes->except('placeholder') }}>
        <button type="button"
            x-ref="anchor"
            x-on:click.stop="open()"
            class="form-input flex items-center gap-3 w-full">
            <div class="shrink-0 text-gray-400"><x-icon name="calendar"/></div>
    
            <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                x-bind:value="value?.toDateString()"
                class="transparent grow cursor-pointer">
    
            <div class="shrink-0">
                <x-close x-show="value" x-on:click="value = null"/>
                <x-icon x-show="!value" name="dropdown-caret"/>
            </div>
        </button>

        <div 
            x-ref="dropdown"
            x-show="show"
            x-anchor.bottom-start.offset.2="$refs.anchor"
            x-transition.opacity.duration.300
            class="z-10">
            <div x-ref="calendar"></div>
        </div>
    </div>
</x-form.field>