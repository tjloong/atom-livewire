@props([
    'format' => $attributes->get('format', 12),
    'value' => $attributes->get('value') ?? data_get($this, $attributes->wire('model')->value()),
])

@if (str($value)->is('*:*:*'))
    @php [$hours, $minutes, $seconds] = explode(':', $value) @endphp
@elseif (str($value)->is('*:* AM'))
    @php $am = 'AM' @endphp
    @php [$hours, $minutes] = explode(':', str($value)->replace(' AM', '')->replace('AM', '')->toString()) @endphp
@elseif (str($value)->is('*:* PM'))
    @php $am = 'PM' @endphp
    @php [$hours, $minutes] = explode(':', str($value)->replace(' PM', '')->replace('PM', '')->toString()) @endphp
@endif

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            format: @js($format),
            input (data) {
                if (this.format === 12 && data.am === 'PM' && !empty(data.hours)) {
                    data.hours = `${+data.hours + 12}`
                }

                if (!empty(data.hours) && !empty(data.minutes)) {
                    this.$dispatch('input', `${data.hours}:${data.minutes}:00`)
                }
                else this.$dispatch('input', null)
            },
        }"
        {{ $attributes->class([
            'form-input flex items-center gap-3',
            $attributes->get('class', 'w-full'),
        ]) }}
    >
        <x-icon name="clock" class="text-gray-500 shrink-0"/>

        <div class="grow flex items-center justify-evenly gap-2">
            <x-dropdown>
                <x-slot:anchor>
                    <input x-ref="hours" type="text" placeholder="{{ $format == 24 ? '24' : '12' }}" readonly
                        value="{{ $hours ?? null }}"
                        class="form-input transparent text-center"
                    >
                </x-slot:anchor>
                
                <x-slot:items class="max-h-[200px] overflow-auto">
                    @foreach ($format === 24 ? range(0, 23) : range(1, 12) as $val)
                        @php $val = str()->padLeft($val, 2, '0') @endphp
                        <x-dropdown.item 
                            x-on:click.stop="input({ 
                                hours: '{{ $val }}',
                                minutes: '{{ $minutes ?? '00' }}',
                                seconds: '00',
                                am: '{{ $am ?? 'AM' }}',
                            })"
                            :label="$val"
                        />
                    @endforeach
                </x-slot:items>
            </x-dropdown>

            <span class="font-bold">:</span>

            <x-dropdown>
                <x-slot:anchor>
                    <input x-ref="minutes" type="text" placeholder="59" readonly
                        value="{{ $minutes ?? null }}"
                        class="form-input transparent text-center"
                    >
                </x-slot:anchor>
                
                <x-slot:items class="max-h-[200px] overflow-auto">
                    @foreach (range(0, 59) as $val)
                        @php $val = str()->padLeft($val, 2, '0') @endphp
                        <x-dropdown.item 
                            x-on:click.stop="input({ 
                                hours: '{{ $hours ?? '00' }}',
                                minutes: '{{ $val }}',
                                seconds: '00',
                                am: '{{ $am ?? 'AM' }}',
                            })"
                            :label="$val"
                        />
                    @endforeach
                </x-slot:items>
            </x-dropdown>
            
            @if ($format == 12)
                <span 
                    x-ref="am"
                    x-on:click.stop="input({ 
                        hours: '{{ $hours ?? '00' }}',
                        minutes: '{{ $minutes ?? '00' }}',
                        seconds: '00',
                        am: '{{ isset($am) && $am === 'AM' ? 'PM' : 'AM' }}',
                    })"
                    class="h-full grow rounded font-medium text-center cursor-pointer hover:bg-gray-100"
                >
                    {{ $am ?? 'AM' }}
                </span>
            @endif
        </div>
    </div>
</x-form.field>

