@props([
    'cols' => $attributes->get('cols'),
])

@if ($slot->isNotEmpty())
    <div {{ 
        $attributes
            ->merge(['class' => 'p-5 border-t mt-4 first:border-t-0 first:mt-0'])
            ->except(['cols', 'label']) 
    }}>
        <div class="flex flex-col gap-4">
            @isset($label) {{ $label }}
            @elseif ($label = $attributes->get('label')) 
                <div class="flex items-center gap-3">
                    <div class="grow text-lg font-medium">
                        {{ __($label) }}
                    </div>

                    @isset($buttons)
                        <div class="shrink-0 flex items-center gap-2">
                            {{ $buttons }}
                        </div>
                    @endisset
                </div>

            @endisset

            <div class="grid gap-6 grid-cols-1 {{
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