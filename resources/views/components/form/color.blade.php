@php
    $colors = [
        'simple' => [
            'gray' => 'bg-gray-500',
            'red' => 'bg-red-500',
            'orange' => 'bg-orange-500',
            'yellow' => 'bg-yellow-500',
            'green' => 'bg-green-500',
            'cyan' => 'bg-cyan-500',
            'blue' => 'bg-blue-500',
            'purple' => 'bg-purple-500',
            'black' => 'bg-black',
            'white' => 'bg-white',
        ],

        'full' => [
            '#f8fafc', '#f1f5f9', '#e2e8f0', '#cbd5e1', '#94a3b8', '#64748b', '#475569', '#334155', '#1e293b', '#0f172a', '#020617',
            '#fafaf9', '#f5f5f4', '#e7e5e4', '#d6d3d1', '#a8a29e', '#78716c', '#57534e', '#44403c', '#292524', '#1c1917', '#0c0a09',
            '#fef2f2', '#fee2e2', '#fecaca', '#fca5a5', '#f87171', '#ef4444', '#dc2626', '#b91c1c', '#991b1b', '#7f1d1d', '#450a0a',
            '#fff7ed', '#ffedd5', '#fed7aa', '#fdba74', '#fb923c', '#f97316', '#ea580c', '#c2410c', '#9a3412', '#7c2d12', '#431407',
            '#fffbeb', '#fef3c7', '#fde68a', '#fcd34d', '#fbbf24', '#f59e0b', '#d97706', '#b45309', '#92400e', '#78350f', '#451a03',
            '#fefce8', '#fef9c3', '#fef08a', '#fde047', '#facc15', '#eab308', '#ca8a04', '#a16207', '#854d0e', '#713f12', '#422006',
            '#f7fee7', '#ecfccb', '#d9f99d', '#bef264', '#a3e635', '#84cc16', '#65a30d', '#4d7c0f', '#3f6212', '#365314', '#1a2e05',
            '#f0fdf4', '#dcfce7', '#bbf7d0', '#86efac', '#4ade80', '#22c55e', '#16a34a', '#15803d', '#166534', '#14532d', '#052e16',
            '#ecfdf5', '#d1fae5', '#a7f3d0', '#6ee7b7', '#34d399', '#10b981', '#059669', '#047857', '#065f46', '#064e3b', '#022c22',
            '#f0fdfa', '#ccfbf1', '#99f6e4', '#5eead4', '#2dd4bf', '#14b8a6', '#0d9488', '#0f766e', '#115e59', '#134e4a', '#042f2e',
            '#ecfeff', '#cffafe', '#a5f3fc', '#67e8f9', '#22d3ee', '#06b6d4', '#0891b2', '#0e7490', '#155e75', '#164e63', '#083344',
            '#f0f9ff', '#e0f2fe', '#bae6fd', '#7dd3fc', '#38bdf8', '#0ea5e9', '#0284c7', '#0369a1', '#075985', '#0c4a6e', '#082f49',
            '#eff6ff', '#dbeafe', '#bfdbfe', '#93c5fd', '#60a5fa', '#3b82f6', '#2563eb', '#1d4ed8', '#1e40af', '#1e3a8a', '#172554',
            '#eef2ff', '#e0e7ff', '#c7d2fe', '#a5b4fc', '#818cf8', '#6366f1', '#4f46e5', '#4338ca', '#3730a3', '#312e81', '#1e1b4b',
            '#f5f3ff', '#ede9fe', '#ddd6fe', '#c4b5fd', '#a78bfa', '#8b5cf6', '#7c3aed', '#6d28d9', '#5b21b6', '#4c1d95', '#2e1065',
            '#faf5ff', '#f3e8ff', '#e9d5ff', '#d8b4fe', '#c084fc', '#a855f7', '#9333ea', '#7e22ce', '#6b21a8', '#581c87', '#3b0764',
            '#fdf4ff', '#fae8ff', '#f5d0fe', '#f0abfc', '#e879f9', '#d946ef', '#c026d3', '#a21caf', '#86198f', '#701a75', '#4a044e',
            '#fdf2f8', '#fce7f3', '#fbcfe8', '#f9a8d4', '#f472b6', '#ec4899', '#db2777', '#be185d', '#9d174d', '#831843', '#500724',
            '#fff1f2', '#ffe4e6', '#fecdd3', '#fda4af', '#fb7185', '#f43f5e', '#e11d48', '#be123c', '#9f1239', '#881337', '#4c0519',
            '#ffffff', '#000000',
        ],
    ];
@endphp

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            show: false,
            value: @entangle($attributes->wire('model')),
            colors: @js($colors),
            fullmode: @js($attributes->get('full', false)),
            get selected () {
                return this.fullmode
                    ? this.value
                    : this.colors.simple[this.value]
            },
            select (color) {
                this.value = color
            },
            isHex (val) {
                return !empty(val) && val.charAt(0) === '#'
            },
        }"
        x-modelable="value"
        x-on:click="show = true"
        x-on:click.away="show = false"
        class="relative">
        <div x-ref="anchor"
            class="form-input w-full">
            <div
                x-bind:class="!selected && 'form-input-caret'" 
                class="flex items-center gap-3">
                <div class="shrink-0">
                    <x-icon name="fill" class="text-gray-400"/>
                </div>

                <div x-show="!empty(selected)" class="shrink-0 flex items-center justify-center">
                    <div
                        x-bind:class="!isHex(selected) && selected"
                        x-bind:style="{ backgroundColor: isHex(selected) ? selected : null }"
                        class="w-5 h-5 rounded-full shadow border"></div>
                </div>

                <input type="text"
                    x-bind:value="value"
                    x-bind:class="!isHex(value) && 'capitalize'"
                    class="transparent grow"
                    placeholder="{{ __($attributes->get('placeholder', 'Select Color')) }}"
                    readonly>

                <div 
                    x-show="!empty(selected)"
                    x-on:click="value = null"
                    class="shrink-0">
                    <x-close/>
                </div>
            </div>
        </div>

        <div x-ref="dd"
            x-show="show"
            x-transition.opacity
            class="absolute z-40 bg-white shadow-lg rounded-lg border border-gray-300 overflow-hidden mt-px">
            <template x-if="fullmode">
                <div class="flex flex-col divide-y">
                    <div class="grow grid grid-cols-11 gap-1 p-1 max-h-[300px] overflow-auto">
                        <template x-for="color in colors.full">
                            <div
                                x-on:click="select(color)"
                                x-bind:style="{ backgroundColor: color }"
                                class="cursor-pointer w-8 h-8 border rounded hover:ring-1 hover:ring-offset-1 hover:ring-gray-500"></div>
                        </template>
                    </div>

                    <div class="p-2">
                        <div class="flex items-center gap-3 form-input">
                            <div class="grow flex items-center">
                                <input type="text"
                                    class="transparent grow"
                                    placeholder="Hex">
                            </div>

                            <div class="shrink-0">
                                <button type="button" class="flex items-center justify-center">
                                    <x-icon name="check" class="text-gray-500"/>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="!fullmode">
                <div class="grid grid-cols-5 gap-1 p-1">
                    <template x-for="(bg, name) in colors.simple">
                        <div
                            x-on:click="select(name)"
                            x-bind:class="bg"
                            class="cursor-pointer w-10 h-10 border rounded hover:ring-1 hover:ring-offset-1 hover:ring-gray-500"></div>
                    </template>
                </div>
            </template>
        </div>
    </div>

    {{-- <div
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
    </div> --}}
</x-form.field>
