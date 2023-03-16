<x-form.field {{ $attributes }}>
    <div
        x-data="{
            wire: @js(!empty($attributes->wire('model')->value())),
            value: @js($attributes->get('value')),
            entangle: @entangle($attributes->wire('model')),
            focusElem: [],
            onloadFocus: @js($attributes->get('focus', false)),
            segments: {
                head: null,
                body: null,
                tail: null,
            },
            init () {
                if (this.wire) {
                    this.value = this.entangle
                    this.$watch('entangle', (val) => {
                        this.value = val
                        this.breakToSegments()
                    })
                }

                this.breakToSegments()

                if (this.onloadFocus) this.$nextTick(() => this.$refs.head.focus())
            },
            breakToSegments () {
                const splits = (this.value || '').split('-')

                if (splits.length > 1) {
                    this.segments.head = splits[0]
                    this.segments.body = splits[1] || null
                    this.segments.tail = splits[2] || null
                }
                else if (+this.value) {
                    this.segments.head = this.value.substring(0, 6)
                    this.segments.body = this.value.substring(6, 8)
                    this.segments.tail = this.value.substring(8)
                }

                this.$watch('segments', (val) => this.input())
            },
            focus (e) {
                this.focusElem.push(e.target)
            },
            blur (e) {
                this.focusElem = this.focusElem.filter(elem => elem !== e.target)
            },
            jump (segment) {
                const elem = this.$refs[segment]
                const max = elem.getAttribute('maxlength')
                const val = this.segments[segment] || ''

                if (val.length >= parseInt(max)) {
                    if (segment === 'head') this.$refs.body.select()
                    if (segment === 'body') this.$refs.tail.select()
                }
                else if (!val.length) {
                    if (segment === 'tail') this.$refs.body.select()
                    if (segment === 'body') this.$refs.head.select()
                }                
            },
            input () {
                const join = [this.segments.head, this.segments.body, this.segments.tail]
                    .filter(Boolean)
                    .map(val => (val.replace(/\D/g, '')))
                    .join('-')

                if (this.wire) this.entangle = join
                else {
                    this.value = join
                    this.$dispatch('input', join)
                }
            },
        }"
        x-bind:class="focusElem.length && 'active'"
        class="form-input {{ component_error($errors, $attributes) ? 'error' : '' }}"
        {{ $attributes->merge(['id' => component_id($attributes)])->whereStartsWith(['id', 'x-']) }}
    >
        <div x-on:input.stop class="flex items-center gap-2">
            <input 
                x-ref="head"
                x-on:focus="focus" 
                x-on:blur="blur"
                x-on:input="jump('head')"
                x-model="segments.head"
                type="text" 
                class="grow appearance-none p-0 border-0 w-16" 
                maxlength="6"
            >
            <span>-</span>
            <input 
                x-ref="body"
                x-on:focus="focus" 
                x-on:blur="blur" 
                x-on:input="jump('body')"
                x-model="segments.body"
                type="text" 
                class="grow appearance-none p-0 border-0 w-6" 
                maxlength="2"
            >
            <span>-</span>
            <input 
                x-ref="tail"
                x-on:focus="focus" 
                x-on:blur="blur" 
                x-on:input="jump('tail')"
                x-model="segments.tail"
                type="text" 
                class="grow appearance-none p-0 border-0 w-14" 
                maxlength="4"
            >
        </div>
    </div>
</x-form.field>
