@php
$label = $attributes->get('label');
$caption = $attributes->get('caption');

$classes = $attributes->classes()
    ->add('inline-flex gap-3')
    ->add($label && !$caption ? 'items-center' : '')
    ;

$attrs = $attributes->class($classes)->except(['label', 'caption']);
@endphp

<label {{ $attrs }} data-atom-checkbox>
    <div class="group shrink-0 {{ $label && $caption ? 'pt-0.5' : '' }}">
        <input
            x-ref="checkbox"
            type="checkbox"
            class="hidden peer" 
            {{ $attributes->except('class') }}>

        <div
            tabindex="0"
            role="checkbox"
            class="w-5 h-5 bg-white rounded-md border border-zinc-300 shadow-sm flex items-center justify-center ring-offset-1 focus:outline-none focus:ring-1 focus:ring-primary peer-checked:bg-primary peer-checked:border-primary group-has-[.error]:ring-1 group-has-[.error]:ring-red-500">
            <atom:icon check size="13" class="text-zinc-300 group-has-[:checked]:text-white"/>
        </div>
    </div>

    @if ($slot->isNotEmpty())
        <div class="grow">
            {{ $slot }}
        </div>
    @elseif ($label && $caption)
        <div class="grow">
            <div>{!! t($label) !!}</div>
            <atom:caption>{!! t($caption) !!}</atom:caption>
        </div>
    @elseif ($label)
        <div class="grow">
            {!! t($label) !!}
        </div>
    @endif
</label>
