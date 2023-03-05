<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            text: null,
            focus: false,
            value: @entangle($attributes->wire('model')) || @js((array)$attributes->get('value')),
            add () {
                this.value.push(this.text)
                this.text = null
            },
            backspace (e) {
                if (e.keyCode !== 8) return
                if (this.text) return
                if (!this.value || !this.value.length) return

                this.value.pop()
            },
            remove (i) {
                this.value.splice(i, 1)
            },
        }"
        x-bind:class="focus && 'active'"
        class="form-input {{ component_error(optional($errors), $attributes) ? 'error' : '' }}"
    >
        <div class="flex flex-wrap items-center gap-2">
            <template x-for="(val, i) in value.filter(Boolean)">
                <div class="shrink-0 text-sm bg-gray-100 border rounded px-2 flex items-center gap-2">
                    <div x-text="val"></div>
                    <div x-on:click="remove(i)" class="cursor-pointer flex text-gray-500">
                        <x-icon name="xmark" size="12"/>
                    </div>
                </div>
            </template>

            <input type="text"
                x-model="text"
                x-on:input.stop
                x-on:focus="focus = true"
                x-on:blur="focus = false"
                x-on:keydown.enter.prevent="add"
                x-on:keydown="backspace"
                class="grow"
            >
        </div>
    </div>
</x-form.field>