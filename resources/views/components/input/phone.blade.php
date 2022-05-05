<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div 
        x-data="phoneInput(@js([
            'code' => '+60',
            'model' => $attributes->wire('model')->value(),
            'value' => $attributes->get('value'),
            'focus' => $attributes->get('focus') ?? false,
        ]))"
        x-on:click.away="show = false"
        class="relative"
    >
        <input x-ref="input" type="hidden" x-bind:value="value" {{ $attributes->except('focus', 'value') }}>

        <div x-bind:class="focus && 'active'" class="form-input flex items-center gap-6">
            <a x-on:click="show = true" class="flex items-center gap-2">
                <img x-show="flag" x-bind:src="flag" class="w-4">
                <div class="text-gray-500" x-text="code"></div>
            </a>

            <input 
                x-ref="tel"
                x-model="tel"
                x-on:focus="focus = true"
                x-on:blur="focus = false"
                x-on:input="pattern"
                type="tel" 
                class="w-full appearance-none border-0 p-0 focus:ring-0"
            >
        </div>

        <div x-show="show" class="absolute left-0 top-full pt-1">
            <div class="grid divide-y bg-white border shadow rounded-md w-max">
                <div class="p-2">
                    <input type="text" x-model="search" class="form-input w-full" placeholder="{{ __('Search country') }}">
                </div>

                <div x-ref="options" class="max-h-[200px] overflow-auto grid divide-y">
                    @foreach (metadata()->countries() as $country)
                        <a
                            x-on:click="select('{{ $country->dial_code }}')"
                            x-show="results === null || results.includes('{{ $country->dial_code }}')"
                            class="flex items-center gap-2 py-2 px-4 hover:bg-gray-100"
                            data-code="{{ $country->dial_code }}"
                            data-name="{{ $country->name }}"
                        >
                            @if ($country->flag)
                                <img src="{{ $country->flag }}" class="w-4">
                            @endif
                            <div class="text-gray-500 shrink-0">{{ $country->dial_code }}</div>
                            <div class="font-medium text-gray-800">{{ $country->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>        
    </div>
</x-input.field>

