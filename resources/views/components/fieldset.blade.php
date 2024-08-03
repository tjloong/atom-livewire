@php
$title = $title ?? $attributes->getAny('title', 'heading');
$icon = $attributes->get('icon');
$box = !$attributes->get('no-box');
$group = $attributes->get('group');
$divide = !$attributes->get('no-divide');
@endphp

@if ($group)
    <div class="py-3 px-5 font-medium bg-gray-50">
        {!! tr($group) !!}
    </div>
@elseif ($box)
    <x-box>
        @if ($title instanceof \Illuminate\View\ComponentSlot)
            <x-heading no-margin :attributes="$title->attributes->merge(['class' => 'p-5 rounded-t-lg'])">
                {{ $title }}
            </x-heading>
        @elseif ($title)
            <x-heading no-margin :title="$title" :icon="$icon" class="p-5 rounded-t-lg"/>
        @endif

        <x-fieldset no-box :attributes="$attributes->merge([
            'no-divide' => false,
        ])->except('no-box')">
            {{ $slot }}
        </x-fieldset>

        @if (isset($foot) && $foot->isNotEmpty())
            <x-slot:foot>
                {{ $foot }}
            </x-slot:foot>
        @endif
    </x-box>
@else
    <fieldset {{ $attributes->class([
        'flex flex-col *:py-3 *:px-5 last:*:rounded-b-lg',
        $divide ? 'divide-y hover:*:bg-slate-50' : null,
        !$title ? 'first:*:rounded-t-lg' : null,
    ]) }}>
        {{ $slot }}
    </fieldset>
@endif