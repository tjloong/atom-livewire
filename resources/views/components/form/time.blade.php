@props([
    'getValue' => function($key) use ($attributes) {
        $val = $attributes->wire('model')->value()
            ? data_get($this, $attributes->wire('model')->value())
            : $attributes->get('value');

        if ($val) {
            $format = $attributes->get('format', 24);
            [$hours, $minutes, $seconds] = explode(':', $val);

            if ($format == 12) {
                $am = $hours > 12 ? 'PM' : 'AM';
                $hours = $hours > 12 ? $hours - 12 : $hours;
            }
            else $am = null;

            $hours = str()->padLeft($hours, 2, '0');
            $minutes = str()->padLeft($minutes, 2, '0');
            $seconds = str()->padLeft($seconds, 2, '0');

            return $$key;
        }
        
        return null;
    },
])

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            focus: false,
            input (hours, minutes, seconds, am = null) {
                if (empty(hours) || empty(minutes)) this.$dispatch('input', null)

                if (am === 'PM') hours = +hours + 12

                this.$dispatch('input', `${hours}:${minutes}:${seconds}`)
            },
        }"
        x-on:click.stop="focus = true"
        x-on:click.away="focus = false"
        x-bind:class="focus && 'active'"
        {{ $attributes->class(['form-input flex items-center gap-3']) }}
    >
        <x-icon name="clock" class="text-gray-500 shrink-0"/>

        <div class="grow flex items-center justify-evenly gap-2">
            <x-dropdown class="grow">
                <x-slot:anchor class="text-center w-full {{ !$getValue('hours') ? 'text-gray-400' : '' }}">
                    {{ $getValue('hours') ?? ($getValue('format') == 24 ? '24' : '12') }}
                </x-slot:anchor>
                
                <x-slot:items class="max-h-[200px] overflow-auto">
                    @foreach (collect(
                        $getValue('format') === 24 ? range(0, 23) : range(1, 12)
                    )->map(fn($n) => str()->padLeft($n, 2, '0')) as $val)
                        <x-dropdown.item 
                            x-on:click="input(
                                '{{ $val }}',
                                '{{ $getValue('minutes') ?? '00' }}',
                                '{{ $getValue('seconds') ?? '00' }}',
                                '{{ $getValue('am') }}',
                            )"
                            :label="$val"
                        />
                    @endforeach
                </x-slot:items>
            </x-dropdown>

            <span class="font-bold">:</span>

            <x-dropdown class="grow">
                <x-slot:anchor class="text-center w-full {{ !$getValue('minutes') ? 'text-gray-400' : '' }}">
                    {{ $getValue('minutes') ?? '59' }}
                </x-slot:anchor>
                
                <x-slot:items class="max-h-[200px] overflow-auto">
                    @foreach (collect(range(0, 59))->map(fn($n) => str()->padLeft($n, 2, '0')) as $val)
                        <x-dropdown.item 
                            x-on:click.stop="input(
                                '{{ $getValue('hours') ?? '00' }}',
                                '{{ $val }}',
                                '{{ $getValue('seconds') ?? '00' }}',
                                '{{ $getValue('am') }}',
                            )"
                            :label="$val"
                        />
                    @endforeach
                </x-slot:items>
            </x-dropdown>
            
            @if ($getValue('format') == 12)
                <span 
                    x-ref="am"
                    x-on:click.stop="input(
                        '{{ $getValue('hours') ?? '00' }}',
                        '{{ $getValue('minutes') ?? '00' }}',
                        '{{ $getValue('seconds') ?? '00' }}',
                        '{{ $getValue('am') === 'AM' ? 'PM' : 'AM' }}',
                    )"
                    class="h-full w-14 rounded font-medium text-center cursor-pointer text-sm hover:bg-gray-100"
                >
                    {{ $getValue('am') ?? 'AM' }}
                </span>
            @else
                <span class="font-bold">:</span>

                <x-dropdown>
                    <x-slot:anchor>
                        <input type="text" placeholder="59" readonly
                            x-ref="seconds"
                            value="{{ $getValue('seconds') }}"
                            class="bg-transparent text-center w-20"
                        >
                    </x-slot:anchor>
                    
                    <x-slot:items class="max-h-[200px] overflow-auto">
                        @foreach (collect(range(0, 59))->map(fn($n) => str()->padLeft($n, 2, '0')) as $val)
                            <x-dropdown.item 
                                x-on:click.stop="input(
                                    '{{ $getValue('hours') ?? '00' }}',
                                    '{{ $getValue('minutes') ?? '00' }}',
                                    '{{ $val }}',
                                    '{{ $getValue('am') }}',
                                    )"
                                :label="$val"
                            />
                        @endforeach
                    </x-slot:items>
                </x-dropdown>
            @endif
        </div>
    </div>
</x-form.field>