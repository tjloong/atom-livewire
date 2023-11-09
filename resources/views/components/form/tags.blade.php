<x-form.field {{ $attributes }}>
    <div
        x-data="{
            value: @entangle($attributes->wire('model')),
            options: @js($attributes->get('options', [])),
            show: false,
            text: null,
            get filteredOptions () {
                return this.options.filter(val => (empty(this.text) || val.includes(this.text)))
            },
            init () {
                if (!this.value) this.value = []
            },
            open () {
                if (!this.show) {
                    this.show = true
                    this.$nextTick(() => this.focus())
                }
            },
            close () {
                this.show = false
                this.text = null
            },
            select (val = null) {
                if (!val && !empty(this.text)) {
                    const opt = this.filteredOptions[0]
                    if (opt) this.value.push(opt)
                    else {
                        this.value.push(this.text)
                        this.options.push(this.text)
                    }
                }
                else if (!this.value.includes(val)) {
                    this.value.push(val)
                }

                this.close()
            },
            remove (val = null) {
                if (!val) {
                    this.value = []
                }
                else {
                    const index = this.value.indexOf(val)
                    this.value.splice(index, 1)
                }

                this.focus()
            },
            backspace (e) {
                if (!this.text || !this.text.length) {
                    this.value.pop()
                    this.focus()
                }
            },
            focus () {
                this.$refs.text.focus()
                floatDropdown(this.$refs.anchor, this.$refs.dd)
            },
        }"
        x-modelable="value"
        x-on:click="open"
        x-on:click.away="close"
        class="relative">
        <div x-ref="anchor"
            x-bind:class="show && 'active'"
            class="form-input w-full flex gap-3">
            @if ($icon = $attributes->get('icon'))
                <div class="shrink-0">
                    <x-icon :name="$icon" class="text-gray-400"/>
                </div>
            @endif

            <div class="grow flex flex-wrap gap-2">
                <template x-for="(val, i) in value.filter(Boolean)">
                    <div class="shrink-0 text-sm bg-gray-100 border rounded px-2 flex items-center gap-2 max-w-xs">
                        <div x-text="val" class="truncate"></div>
                        <div x-on:click="remove(val)" class="cursor-pointer flex text-gray-500">
                            <x-icon name="xmark" size="12"/>
                        </div>
                    </div>
                </template>    

                <input type="text"
                    x-ref="text"
                    x-model="text"
                    x-on:input.stop="open()"
                    x-on:keydown.enter.prevent="select()"
                    x-on:keydown.backspace="backspace"
                    class="grow max-w-sm w-full"
                    placeholder="{{ __($attributes->get('placeholder', 'Add tags')) }}">
            </div>

            <div x-show="value && value.length" class="shrink-0">
                <x-close x-on:click.stop="remove()"/>
            </div>
        </div>

        <div x-ref="dd"
            x-show="show"
            x-transition
            class="absolute z-20 min-w-full">
            <div
                x-show="!filteredOptions.length && text && text.length" 
                class="bg-white shadow-lg rounded-lg border py-2 px-4 text-sm text-gray-500 font-medium text-center">
                {{ tr('common.label.enter-add-tag') }}
            </div>

            <div x-show="filteredOptions.length" class="bg-white shadow-lg rounded-lg border flex flex-col divide-y">
                <template x-for="opt in filteredOptions">
                    <div
                        x-on:click.stop="select(opt)"
                        class="py-2 px-4 cursor-pointer flex items-center gap-3 hover:bg-slate-50">
                        <x-icon name="tag" class="text-gray-400"/>
                        <span x-text="opt"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-form.field>