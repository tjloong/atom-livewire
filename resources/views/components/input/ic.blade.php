<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    @if ($slot->isNotEmpty())
        <x-slot name="label">{{ $slot }}</x-slot>
    @endif

    <div
        x-data="icInput(@js([
            'focus' => $attributes->get('focus') ?? false,
            'model' => $attributes->wire('model')->value(),
            'value' => $attributes->get('value'),
        ]))"
        class="relative w-52"
    >
        <input x-ref="input" type="hidden" x-bind:value="value" {{ $attributes->except('focus') }}>

        <input 
            x-bind:class="focusElem.length && 'active'"
            class="form-input w-full" 
            type="text" 
            readonly
        >

        <div class="absolute inset-0 flex">
            <div class="my-auto mx-4 flex items-center gap-2">
                <input 
                    x-ref="head"
                    x-on:focus="focus" 
                    x-on:blur="blur"
                    x-on:input="pattern('head')"
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
                    x-on:input="pattern('body')"
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
                    x-on:input="pattern('tail')"
                    x-model="segments.tail"
                    type="text" 
                    class="appearance-none p-0 border-0 w-14" 
                    maxlength="4"
                >
            </div>
        </div>
    </div>
</x-input.field>