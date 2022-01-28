@if ($attributes->get('href'))
<a x-data="button" {{ $attributes->merge(['class' => $styles]) }}>
    <x-loader class="mr-1.5" size="{{ $size === 'xs' ? '14px' : '18px' }}"/>

    @if ($attributes->get('icon'))
        <x-icon name="{{ $attributes->get('icon') }}" class="icon mr-1.5" size="{{ $size === 'xs' ? '14px' : '18px' }}"/>
    @endif

    {{ $slot }}
</a>

@else
<button x-data="button" {{ $attributes->merge(['type' => 'button', 'class' => $styles]) }}>
    <x-loader class="mr-1.5" size="{{ $size === 'xs' ? '14px' : '18px' }}"/>

    @if ($attributes->get('icon'))
        <x-icon name="{{ $attributes->get('icon') }}" class="icon mr-1.5" size="{{ $size === 'xs' ? '14px' : '18px' }}"/>
    @endif

    {{ $slot }}
</button>

@endif
