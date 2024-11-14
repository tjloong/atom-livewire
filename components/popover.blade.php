@php
$placement = $attributes->get('placement');
@endphp

<div popover
    x-on:toggle="() => {
        if ($event.newState === 'open') {
            $el.setAttribute('data-open', true)
            $el.addClass('opacity-100')
            $el.anchorTo($el.parentNode.querySelector('* > [data-anchor]') || $refs?.trigger, {
                placement: @js($placement),
            })
            $el.dispatch('popover-open')
        }
        else if ($event.newState === 'closed') {
            $el.removeClass('opacity-100')
            $el.removeAttribute('data-open')
            $el.anchorCleanup()
            $el.dispatch('popover-closed')
        }
    }"
    {{ $attributes
        ->class(['transition duration-500 ease-in-out opacity-0'])
        ->merge(['x-ref' => 'popover']) }}>
    {{ $slot }}
</div>