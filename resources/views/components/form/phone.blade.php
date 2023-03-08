<x-form.field {{ $attributes }}>
    <div
        x-data="{
            show: false,
            text: null,
            code: '+60',
            focus: false,
            tel: null,
            value: @js($attributes->get('value')) || @entangle($attributes->wire('model')),
            countries: @js(metadata()->countries()),
            get flag () {
                return this.countries.find(cn => (cn.dial_code === this.code))?.flag
            },
            get options () {
                return this.countries.filter(cn => (
                    !this.text || cn.name.toLowerCase().includes(this.text.toLowerCase())
                ))
            },
            decode () {
                const country = this.countries.find(cn => (this.value?.startsWith(cn.dial_code)))
                if (country) {
                    this.code = country.dial_code
                    this.tel = this.value.replace(this.code, '').replace('+', '')
                }
            },
            select (code) {
                this.code = code
                this.input()
                this.close()
            },
            open () {
                this.show = true
                this.$nextTick(() => {
                    floatDropdown(this.$refs.anchor, this.$refs.dd)
                    this.$refs.search.focus()
                })
            },
            close () {
                this.show = false
                this.text = null
            },
            input () {
                this.tel = this.tel ? this.tel.replace(/\D/g, '') : null
                this.value = this.tel ? `${this.code}${this.tel}` : null
            },
        }"
        x-init="decode()"
        x-on:click.away="close()"
        class="relative"
    >
        <div 
            x-ref="anchor"
            x-bind:class="{ 'active': focus || show }" 
            class="form-input flex items-center gap-6 {{
                component_error(optional($errors), $attributes) ? 'error' : ''
            }}"
        >
            <a x-on:click="open()" class="flex items-center gap-2">
                <img x-show="flag" x-bind:src="flag" class="w-4">
                <div class="text-gray-500" x-text="code"></div>
            </a>

            <input 
                type="tel" 
                x-ref="tel"
                x-model="tel"
                x-on:focus="focus = true"
                x-on:blur="focus = false"
                x-on:input="input"
                class="w-full appearance-none border-0 p-0 focus:ring-0"
            >
        </div>

        <div 
            x-ref="dd"
            x-show="show" 
            x-transition.opacity
            class="absolute z-20 w-full bg-white border border-gray-300 shadow-lg rounded-md grid divide-y overflow-hidden"
        >
            <div class="py-2 px-4 bg-gray-100 flex items-center gap-2">
                <x-icon name="search" size="16px" class="text-gray-400"/>
                <div class="grow">
                    <input 
                        type="text" 
                        x-ref="search"
                        x-model="text" 
                        x-on:keydown.enter.prevent="select(options[0]?.dial_code)"
                        class="bg-transparent appearance-none border-0 p-0 focus:ring-0 w-full"
                        placeholder="{{ __('Search') }}"
                    >
                </div>
            </div>

            <div class="h-[250px] overflow-auto">
                <div class="grid divide-y">
                    <template x-for="opt in options">
                        <div 
                            x-on:click="select(opt.dial_code)"
                            class="flex items-center gap-2 py-2 px-4 hover:bg-gray-100 cursor-pointer"
                        >
                            <img x-show="opt.flag" x-bind:src="opt.flag" class="w-4">
                            <div x-text="opt.dial_code" class="text-gray-500 shrink-0"></div>
                            <div x-text="opt.name" class="font-medium text-gray-800"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>        
    </div>
</x-form.field>
