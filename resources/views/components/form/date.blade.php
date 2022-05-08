<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <div
        x-data="dateInput(@js([
            'model' => $attributes->wire('model')->value(),
            'value' => $attributes->get('value'),
            'settings' => [
                'minDate' => $attributes->get('min'),
                'maxDate' => $attributes->get('max'),
            ],
        ]))"
        x-on:click.away="close()"
        class="relative"
        {{ $attributes->except('error', 'required', 'caption') }}
    >
        <div class="absolute top-0 bottom-0 text-gray-400 flex items-center justify-center px-2.5">
            <x-icon name="calendar" size="20px"/>
        </div>

        <input
            x-bind:value="formatDate(value)"
            x-on:focus="open()"
            type="text"
            class="w-full form-input px-10 cursor-pointer {{ !empty($attributes->get('error')) ? 'error' : '' }}"
            placeholder="Pick a date"
            readonly
        >

        <div x-show="loading" class="absolute top-0 bottom-0 right-0 flex items-center justify-center px-1">
            <x-loader size="18px"/>
        </div>

        <a
            class="absolute top-0 bottom-0 right-0 text-gray-500 flex items-center justify-center px-2.5"
            x-on:click="clear()"
            x-show="!loading && value !== null"
        >
            <x-icon name="x" size="20px"/>
        </a>

        <div
            x-ref="dropdown"
            x-show="show"
            class="absolute z-10"
        >
            <div x-ref="datepicker"></div>
        </div>
    </div>
</x-form.field>

