@props(['model' => $attributes->wire('model')->value()])

<div
    x-data="{
        show: false,
        model: @js($model),
        value: @js($attributes->get('value')),
        init () {
            if (this.model) {
                this.value = this.$wire.get(this.model)
                this.$watch('value', () => this.$wire.set(this.model, this.value))
            }
        },
    }"
    x-bind:class="show && 'fixed inset-0 z-20 md:static'"
>
    <div x-show="show" class="absolute inset-0 bg-black opacity-50 md:hidden"></div>
    <div
        x-on:click="show = false"
        x-bind:class="show && 'absolute inset-0 px-6 pt-6 pb-16 md:static md:p-0'"
    >
        <div
            x-on:click.stop
            x-bind:class="show && 'bg-white rounded-md shadow max-w-sm mx-auto p-4 h-[500px] overflow-auto md:bg-transparent md:shadow-none md:max-w-none md:p-0 md:h-auto md:rounded-none'"
        >
            {{ $slot }}
        </div>
    </div>
</div>
