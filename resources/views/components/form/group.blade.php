@props([
    'cols' => $attributes->get('cols'),
])

@if ($slot->isNotEmpty())
    <div {{ $attributes->class([
        'flex flex-col gap-2 border-t first:border-t-0',
        $attributes->get('class', 'p-5')
    ])->except('cols') }}>
        @isset($heading)
            {{ $heading }}
        @elseif ($heading = $attributes->get('heading'))
            <x-heading :title="$heading" class="mb-4" sm/>
        @endif

        <div class="grid gap-5 grid-cols-1 {{
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
@endif