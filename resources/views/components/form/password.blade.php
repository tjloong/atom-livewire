<x-form.field {{ $attributes }}>
    <div class="relative w-full" x-data="{ show: false }">
        <input
            x-bind:type="show ? 'text' : 'password'"
            {{ $attributes->class([
                'form-input w-full pr-12',
                'error' => component_error(optional($errors), $attributes),
            ]) }}
        >
        <a
            class="absolute top-0 right-0 bottom-0 px-4 flex items-center justify-center text-gray-900"
            x-on:click="show = !show"
        >
            <x-icon x-show="!show" name="show"/>
            <x-icon x-show="show" name="hide"/>
        </a>
    </div>
</x-form.field>
