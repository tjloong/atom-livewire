@if ($errors)
    <div {{ $attributes->class([
        'p-4 rounded-md grid gap-1', 
        data_get($color, 'bg'),
        data_get($color, 'border'),
    ])->except('errors') }}>
        @foreach ($errors as $error)
            <div class="flex items-center gap-2">
                <x-icon :name="$icon" class="{{ data_get($color, 'icon') }} shrink-0" size="20px"/>
                <div class="{{ data_get($color, 'text') }} font-medium">{{ __($error) }}</div>
            </div>
        @endforeach
    </div>
@else
    <div {{ $attributes->class([
        'p-4 rounded-md flex gap-2', 
        data_get($color, 'bg'), 
        data_get($color, 'border'),
    ])->except('errors') }}>
        <x-icon name="{{ $icon }}" class="{{ data_get($color, 'icon') }} shrink-0 py-0.5" size="20"/>
        
        <div class="grow grid gap-2 self-center">
            @isset($title) 
                <div class="{{ data_get($color, 'title') }} font-semibold text-lg">
                    {{ $title }}
                </div>
            @elseif ($title = $attributes->get('title'))
                <div class="{{ data_get($color, 'title') }} font-semibold text-lg">
                    {{ __(str(__($title))->toHtmlString()) }}
                </div>
            @endisset

            <div class="{{ data_get($color, 'text') }} font-medium">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
