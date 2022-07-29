<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ __($label) }}</x-slot:label>
    @endif

    <div
        x-data="{
            value: @js($attributes->get('value')) || @entangle($attributes->wire('model')),
            focusElem: [],
            onloadFocus: @js($attributes->get('focus', false)),
        
            segments: {
                head: null,
                body: null,
                tail: null,
            },
        
            init () {
                if (this.onloadFocus) this.$nextTick(() => this.$refs.head.focus())
        
                const splits = this.value.split('-')
    
                if (splits.length > 1) {
                    this.segments.head = splits[0]
                    this.segments.body = splits[1] || null
                    this.segments.tail = splits[2] || null
                }
                else if (+this.value) {
                    this.segments.head = val.substring(0, 6)
                    this.segments.body = val.substring(6, 8)
                    this.segments.tail = val.substring(8)
                }
            },
        
            focus (e) {
                this.focusElem.push(e.target)
            },
        
            blur (e) {
                this.focusElem = this.focusElem.filter(elem => elem !== e.target)
            },
        
            input (segment) {
                if (this.segments[segment]) this.segments[segment] = this.segments[segment].replace(/\D/g, '')
        
                const elem = this.$refs[segment]
                const max = elem.getAttribute('maxlength')
                const val = this.segments[segment]
        
                if (val.length >= parseInt(max)) {
                    if (segment === 'head') this.$refs.body.select()
                    if (segment === 'body') this.$refs.tail.select()
                }
                else if (!val.length) {
                    if (segment === 'tail') this.$refs.body.select()
                    if (segment === 'body') this.$refs.head.select()
                }
        
                this.value = [this.segments.head, this.segments.body, this.segments.tail].filter(Boolean).join('-')
            },
        }"
        x-bind:class="focusElem.length && 'active'"
        class="form-input w-52 flex items-center gap-2"
    >
        <input 
            x-ref="head"
            x-on:focus="focus" 
            x-on:blur="blur"
            x-on:input="input('head')"
            x-model="segments.head"
            type="text" 
            class="appearance-none p-0 border-0 w-16" 
            maxlength="6"
        >
        <span>-</span>
        <input 
            x-ref="body"
            x-on:focus="focus" 
            x-on:blur="blur" 
            x-on:input="input('body')"
            x-model="segments.body"
            type="text" 
            class="appearance-none p-0 border-0 w-6" 
            maxlength="2"
        >
        <span>-</span>
        <input 
            x-ref="tail"
            x-on:focus="focus" 
            x-on:blur="blur" 
            x-on:input="input('tail')"
            x-model="segments.tail"
            type="text" 
            class="appearance-none p-0 border-0 w-14" 
            maxlength="4"
        >
    </div>
</x-form.field>
