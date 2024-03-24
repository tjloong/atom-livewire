@php
    $code = $attributes->get('code', '+60');
    $placeholder = $attributes->get('placeholder', 'app.label.phone-number');
    $except = ['label', 'caption', 'placeholder'];
@endphp

<x-form.field {{ $attributes }}>
    <div
        x-cloak
        x-data="{
            wirevalue: @entangle($attributes->wire('model')),
            input: null,
            focus: false,
            search: null,
            option: null,
            options: [],
            dropdown: false,
            code: @js($code),
            number: null,
            get filtered () {
                return this.options.filter(opt => (
                    !this.search
                    || opt.value.includes(this.search)
                    || opt.label.toLowerCase().includes(this.search.toLowerCase())
                ))
            },
            format () {
                const val = `${this.code}${this.number}`
                this.input = val.length ? val : null
                this.$dispatch('input', this.input)
            },
            open () {
                this.dropdown = true
                this.$nextTick(() => $(this.$refs.search).find('input').focus())
            },
            initInput (val) {
                const find = this.options.find(opt => (val.startsWith(opt.value)))

                if (find) {
                    this.select(find)
                    this.number = val.replace(this.code, '').replace('+', '')
                }
            },
            select (opt) {
                if (!opt) return
                this.option = { ...opt }
                this.code = opt.value
                this.search = null
            },
        }"
        x-modelable="input"
        x-init="$nextTick(() => {
            axios.post(@js(route('__select.get')), { callback: 'dial_codes' }).then(({ data }) => {
                options = [...data]

                if (wirevalue) initInput(wirevalue)
                else if (code) {
                    const find = options.find(opt => (opt.value === code))
                    if (find) select(find)
                }
            })

            $watch('code', () => format())
            $watch('number', () => format())
            $watch('wirevalue', wirevalue => initInput(wirevalue))
        })"
        class="relative"
        {{ $attributes->except($except) }}>
        <div 
            x-ref="anchor"
            x-bind:class="focus && 'active'"
            class="form-input flex items-center gap-3">
            <div 
                x-on:click="open()"
                x-on:click.away="dropdown = false"
                class="cursor-pointer flex items-center gap-2">
                <div class="w-4 h-4 flex">
                    <img x-bind:src="option?.flag" x-show="option" class="w-full h-full object-contain">
                </div>
                <div class="text-gray-500" x-text="code"></div>
                <x-icon name="dropdown-caret"/>
            </div>

            <input 
                type="tel" 
                x-ref="tel"
                x-model="number"
                x-on:input.stop
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
                    x-on:keydown.enter.prevent="filtered.length && select(filtered[0])"
                    placeholder="app.label.search"/>
            </div>

            <div class="max-h-[250px] overflow-auto">
                <div class="flex flex-col divide-y">
                    <template x-for="opt in filtered">
                        <div x-on:click="select(opt)" class="flex items-center gap-2 py-2 px-4 hover:bg-gray-100 cursor-pointer">
                            <div class="shrink-0 w-4 h-4 flex">
                                <img x-bind:src="opt.flag" x-show="opt.flag" class="w-full h-full object-contain">
                            </div>
                            <div class="shrink-0 text-gray-500" x-text="opt.value"></div>
                            <div class="grow font-medium" x-text="opt.label"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-form.field>