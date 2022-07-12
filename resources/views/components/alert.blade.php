@if ($errors)
    <div {{ $attributes->class(['p-4 rounded-md grid gap-1', $color->bg])->except('errors') }}>
        @foreach ($errors as $error)
            <div class="flex items-center gap-2">
                <x-icon :name="$icon" class="{{ $color->icon }} flex-shrink-0" size="20px"/>
                <div class="{{ $color->text }} font-medium">{{ $error }}</div>
            </div>
        @endforeach
    </div>
@else
    <div {{ $attributes->class(['p-4 rounded-md flex gap-2', $color->bg])->except('errors') }}>
        <x-icon name="{{ $icon }}" class="{{ $color->icon }} shrink-0 py-0.5"/>
        
        <div class="grow grid gap-2">
            @isset($title) 
                <div class="{{ $color->title }} font-semibold text-lg">{{ $title }}</div>
            @elseif ($title = $attributes->get('title'))
                <div class="{{ $color->title }} font-semibold text-lg">{{ str(__($title))->toHtmlString() }}</div>
            @endisset

            <div class="{{ $color->text }} font-medium">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
