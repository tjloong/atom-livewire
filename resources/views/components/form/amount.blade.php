<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label']) }}>
    <div 
        x-data="{
            value: @js($attributes->get('value')) || @entangle($attributes->wire('model')),
            focus: false,
        }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ !empty($attributes->get('error')) ? 'error' : '' }}"
    >
        @if ($prefix = $attributes->get('prefix'))
            <div class="text-gray-400 font-medium">
                {{ $prefix }}
            </div>
        @endif
        
        <input 
            x-model="value"
            x-on:focus="focus = true"
            x-on:blur="focus = false"
            type="number"
            class="form-input transparent grow"
            step=".01"
        >

        @if ($postfix = $attributes->get('postfix'))
            <div class="text-gray-400 font-medium">
                {{ $postfix }}
            </div>
        @endif
    </div>
</x-form.field>
