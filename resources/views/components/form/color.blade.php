<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label']) }}>
    <div
        x-data="{
            pickr: null,
            wire: @js($attributes->wire('model')->value()),
            value: @js($attributes->get('value')),
            entangle: @entangle($attributes->wire('model')),
            config: @js($attributes->get('config')),
            init () {
                if (this.wire) {
                    this.value = this.entangle
                    this.$watch('entangle', (val) => {
                        this.value = val
                        this.pickr.setColor(val)
                    })
                }

                this.createPickr()
            },
            createPickr () {
                this.pickr = Pickr.create({
                    el: '.color-picker',
                    theme: 'monolith',
                    default: this.value,
                    components: {
                        preview: true,
                        opacity: false,
                        hue: true,
                        interaction: {
                            hex: false,
                            rgba: false,
                            hsla: false,
                            hsva: false,
                            cmyk: false,
                            input: false,
                            clear: false,
                            save: false,
                        },
                    },
                    ...this.config,
                })

                this.pickr.on('changestop', (source, instance) => {
                    const color = this.pickr.getColor()
                    const hex = color.toHEXA().toString()

                    this.value = hex

                    if (this.wire) this.entangle = this.value
                    else this.$dispatch('input', this.value)
                })
            },
        }"
        class="p-2 bg-gray-200 inline-block rounded"
        wire:ignore
    >
        <div class="color-picker"></div>
    </div>
</x-form.field>
