@php
$align = $attributes->get('align', 'left');
$checkbox = $attributes->get('checkbox');

$classes = $attributes->classes()
    ->add('flex py-3 px-4 whitespace-nowrap')
    ->add(match ($align) {
        'left' => 'justify-start',
        'center' => 'justify-center',
        'right' => 'justify-end',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['align'])
    ;
@endphp

@if ($checkbox)
    <td
        x-on:click.stop
        valign="{{ $attributes->get('valign', 'top') }}">
        <div {{ $attrs }}>
            <div
                x-on:click="checkboxes.toggle(@js($checkbox))"
                x-on:select="checkboxes.push(@js($checkbox))"
                x-bind:class="checkboxes.includes(@js($checkbox)) ? 'border-primary bg-primary' : 'border-zinc-300 bg-white'"
                x-bind:data-checked="checkboxes.includes(@js($checkbox))"
                data-atom-cell-checkbox
                class="w-6 h-6 rounded-md border flex items-center justify-center cursor-pointer">
                <x-icon check size="14" class="text-white"/>
            </div>
        </div>
    </td>
@else
    <td {{ $attributes->only('colspan') }} valign="{{ $attributes->get('valign', 'top') }}">
        <div {{ $attrs }}>
            {{ $slot }}
        </div>
    </td>
@endif
