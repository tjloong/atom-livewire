@props([
    'cols' => $attributes->get('cols'),
])

@if ($slot->isNotEmpty())
    <div {{ 
        $attributes
            ->merge(['class' => 'p-5 border-t first:border-t-0'])
            ->except(['cols', 'label']) 
    }}>
        <div class="flex flex-col gap-4">
            @isset($label) {{ $label }}
            @elseif ($label = $attributes->get('label')) <div class="text-lg font-medium">{{ __($label) }}</div>
            @endisset

            <div class="grid gap-6 {{
                [
                    2 => 'md:grid-cols-2',
                    3 => 'md:grid-cols-3',
                    4 => 'md:grid-cols-4',
                    5 => 'md:grid-cols-5',
                    6 => 'md:grid-cols-6',
                ][$cols] ?? ''
            }}">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif