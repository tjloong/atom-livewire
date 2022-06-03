<div
    x-data="{
        name: null,
        value: @js($attributes->get('value')),
        checked: false,
        getName () {
            this.name = $el.closest('table').getAttribute('uid')
        },
        toggle () {
            const data = { name: this.name, value: this.value }
            this.$dispatch('table-checkbox-check', data)
            this.$wire && this.$wire.emit('table-checkbox-check', data)
        },
        verify (selected) {
            this.checked = selected.includes(this.value)
                || selected.includes('all')
                || selected.includes('everything')
        }
    }"
    x-init="getName"
    x-on:click="toggle"
    x-on:table-checkbox-checked.window="verify($event.detail)"
    x-bind:class="checked ? 'border-2 border-theme' : 'border border-gray-400'"
    class="w-5 h-5 rounded bg-white shadow flex table-checkbox"
>
    <div x-show="checked" class="w-3 h-3 bg-theme m-auto"></div>
</div>