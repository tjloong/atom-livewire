@php
$icon = $attributes->get('icon');
$align = $attributes->get('align');

$classes = $attributes->classes()
    ->add('flex items-center gap-2 select-none font-medium leading-6 text-zinc-800')
    ->add(match ($align) {
        'right' => 'justify-end',
        'center' => 'justify-center',
        default => 'justify-start',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['icon', 'align'])
    ;
@endphp

@isset ($actions)
    <div class="group flex items-center gap-2 justify-between">
        <atom:_label :attributes="$attributes">
            {{ $slot }}
        </atom:_label>

        <div class="shrink-0 hidden group-hover:block">
            @if ($actions->isEmpty())
                <div
                    @if ($actions->attributes->has('tooltip'))
                    x-tooltip="{{ js(t($actions->attributes->get('tooltip'))) }}"
                    @endif
                    class="w-5 h-5 flex items-center justify-center text-muted-more cursor-pointer"
                    {{ $actions->attributes->except(['tooltip', 'icon']) }}>
                    <atom:icon :name="$actions->attributes->get('icon')" size="13"/>
                </div>
            @else
                {{ $actions }}
            @endif
        </div>
    </div>
@else
    <label {{ $attrs }} data-atom-label>
        @if ($icon)
            <atom:icon :name="$icon" size="15" class="shrink-0"/>
        @endif

        {{ $slot }}
    </label>
@endisset
