@php
$autoresize = $attributes->get('autoresize', true);
$placeholder = $attributes->get('placeholder');
@endphp

<x-form.field {{ $attributes->except('class') }}>
    <textarea placeholder="{!! tr($placeholder) !!}" {{ $attributes->class([
        $autoresize ? 'resize-none' : '',
        $attributes->get('class', 'form-input w-full overflow-hidden')
    ])->only('class') }} {{ $attributes->except('class') }}>
    </textarea>

    @if ($autoresize)
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
    @endif
</x-form.field>
