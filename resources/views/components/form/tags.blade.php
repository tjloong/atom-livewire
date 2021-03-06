<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <div
        x-data="formTags(
            @entangle($attributes->wire('model')->value()), 
            @js($attributes->get('options'))
        )"
        x-on:click.away="close()"
        wire:ignore
        class="relative"
        {{ $attributes->whereStartsWith('wire') }}
    >
        <div 
            x-ref="input"
            x-on:click="open()"
            class="
                w-full flex flex-wrap items-center cursor-pointer border rounded-md p-1.5
                {{ empty($attributes->get('error'))
                    ? 'border-gray-300 hover:ring-2 hover:ring-gray-200'
                    : 'border-red-500 ring-red-200'
                }}
            "
        >
            <template x-for="val in options.filter(opt => (opt.selected))" x-bind:key="val.value">
                <div class="bg-gray-200 rounded border border-gray-300 flex items-center m-1 leading-tight">
                    <div
                        x-text="val.label"
                        class="text-gray-800 font-medium text-sm max-w-[150px] truncate py-1 pl-2"
                    ></div>
                    <a x-on:click.stop="toggle(val)" class="flex items-center justify-center px-1.5 text-gray-400">
                        <x-icon name="x" size="18px"/>
                    </a>
                </div>
            </template>

            <div class="text-gray-400 py-px px-1.5 flex-grow flex items-center">
                <div class="flex-grow">
                    {{ $attributes->get('placeholder') ?? 'Please select' }}
                </div>
                <x-icon name="chevron-down" class="flex-shrink-0"/>
            </div>
        </div>

        <div x-ref="dropdown" class="absolute py-1 z-10 w-full hidden">
            <div x-show="!options.length" class="bg-white drop-shadow rounded-md border p-4">
                <div class="flex items-center justify-center gap-1">
                    <x-icon name="info-circle" class="text-gray-400" size="20px"/>
                    <span class="font-medium text-gray-500">The list is empty</span>
                </div>
            </div>

            <div x-show="options.length" class="bg-white drop-shadow rounded-md border grid divide-y max-h-[250px] overflow-auto">
                <template x-for="opt in options" x-bind:key="opt.value">
                    <a
                        x-on:click.prevent="toggle(opt)"
                        x-bind:class="opt.selected ? 'bg-gray-100 font-semibold' : 'font-medium'"
                        class="py-2 px-4 text-gray-800 flex hover:bg-gray-100"
                    >
                        <div x-show="opt.selected" class="text-green-500 flex items-center justify-center mr-2">
                            <x-icon name="check-circle" size="18px"/>
                        </div>
                        <div x-text="opt.label" class="leading-tight"></div>
                    </a>
                </template>
            </div>
        </div>
    </div>
</x-form.field>
