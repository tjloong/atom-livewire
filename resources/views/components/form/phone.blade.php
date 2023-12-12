@php
    $countries = countries()->map(fn($val) => [
        'flag' => data_get($val, 'flag'),
        'code' => data_get($val, 'dial_code'),
        'name' => data_get($val, 'name'),
    ])->toArray();
@endphp

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            value: @entangle($attributes->wire('model')),
            focus: false,
            search: null,
            dropdown: false,
            countries: @js($countries),
            inputs: {
                code: '+60',
                tel: null,
            },
            get flag () {
                return this.countries.find(val => (val.code === this.inputs.code))?.flag
            },
            get options () {
                if (!this.search) return this.countries

                return this.countries.filter(val => {
                    const haystack = `${val.code} ${val.name}`
                    return haystack.toLowerCase().includes(this.search.toLowerCase())
                })
            },
            open () {
                this.dropdown = true
                this.$nextTick(() => this.$refs.search.querySelector('input').focus())
            },
            select (code) {
                this.inputs.code = code
                this.dropdown = false
                this.search = false
            },
        }"
        x-init="() => {
            if (value) {
                const country = countries.find(val => (value.startsWith(val.code)))
                inputs.code = country?.code
                inputs.tel = value.replace(inputs.code, '').replace('+', '')
            }

            $watch('inputs', () => {
                if (inputs.code && inputs.tel) value = `${inputs.code}${inputs.tel}`
                else value = null
            })
        }"
        x-modelable="value"
        x-on:click.stop="focus = true"
        x-on:click.away="focus = dropdown = false"
        class="relative">
        <div x-ref="anchor" x-bind:class="{ 'active': focus }" class="form-input flex items-center gap-3">
            <a x-on:click="open()" class="flex items-center gap-2">
                <div class="w-4 h-4 flex">
                    <template x-if="flag">
                        <img x-bind:src="flag" class="w-full h-full object-contain">
                    </template>                    
                </div>
                <div class="text-gray-500" x-text="inputs.code"></div>
                <x-icon name="dropdown-caret"/>
            </a>

            <input 
                type="tel" 
                x-ref="tel"
                x-model="inputs.tel"
                class="w-full appearance-none border-0 p-0 focus:ring-0">
        </div>

        <div 
            x-ref="dropdown"
            x-show="dropdown" 
            x-transition.opacity
            class="absolute z-10 top-full w-max bg-white border shadow mt-1 rounded-md overflow-hidden">
            <div x-ref="search" class="p-3 border-b">
                <x-form.text icon="search"
                    x-model="search" 
                    x-on:keydown.enter.prevent="options.length && select(options[0].code)"
                    placeholder="app.label.search"/>
            </div>

            <div class="max-h-[250px] overflow-auto">
                <div class="flex flex-col divide-y">
                    <template x-for="opt in options">
                        <div x-on:click="select(opt.code)" class="flex items-center gap-2 py-2 px-4 hover:bg-gray-100 cursor-pointer">
                            <div class="shrink-0 w-4 h-4 flex">
                                <template x-if="opt.flag">
                                    <img x-bind:src="opt.flag" class="w-full h-full object-contain">
                                </template>                    
                            </div>
                            <div class="shrink-0 text-gray-500" x-text="opt.code"></div>
                            <div class="grow font-medium" x-text="opt.name"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-form.field>
