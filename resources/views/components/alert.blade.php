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
        <x-icon name="{{ $icon }}" class="{{ $color->icon }} flex-shrink-0"/>
        
        <div class="self-center flex-grow">
            @isset($title)
                <div class="{{ $color->title }} text-lg font-semibold mb-1.5">
                    {{ $title }}
                </div>
            @endisset

            <div class="{{ $color->text }} font-medium">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
