<div {{ $attributes->class([
    'p-4 rounded-md flex space-x-2',
    $color->bg,
]) }}>
    <x-icon name="{{ $icon }}" class="{{ $color->icon }} flex-shrink-0"/>
    
    <div class="self-center flex-grow">
        @isset($title)
            <div class="{{ $color->title }} font-semibold mb-1.5">
                {{ $title }}
            </div>
        @endisset

        <div class="{{ $color->text }} font-medium">
            {{ $slot }}
        </div>
    </div>
</div>