<x-form.field {{ $attributes }}>
    <div
        x-cloak
        x-data="{
            show: false,
            focus: false,
        }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-3">
        <input
            x-bind:type="show ? 'text' : 'password'"
            x-on:focus="focus = true"
            x-on:blur="focus = false"
            class="grow form-input transparent"
            {{ $attributes->wire('model') }}>

        <div
            x-on:click.stop="show = !show" 
            class="shrink-0 flex items-center justify-center cursor-pointer">
            <x-icon x-show="show" name="hide"/>
            <x-icon x-show="!show" name="show"/>
        </div>
    </div>
</x-form.field>
