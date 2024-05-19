@php
$placeholder = $attributes->get('placeholder');
@endphp

<x-form.field {{ $attributes->except('class') }}>
    <textarea
        placeholder="{!! tr($placeholder) !!}"
        class="{{ $attributes->get('class', 'form-input w-full overflow-hidden resize-none') }}"
        {{ $attributes->except('class') }}>
    </textarea>

    <template x-data="{
        textarea: null,

        init () {
            this.textarea = this.$root.parentNode.querySelector('textarea') 
            this.$nextTick(() => this.resize())
            this.textarea.addEventListener('input', () => this.resize())
            Livewire.hook('element.updated', (el, component) => el.isEqualNode(this.textarea) && this.resize())
        },

        resize () {
            this.textarea.style.height = 'auto'
            this.textarea.style.height = this.textarea.scrollHeight+20+'px'
        },
    }" hidden></template>
</x-form.field>
