@php
$href = $attributes->get('href');
$target = $attributes->get('target', '_self');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$count = $attributes->get('count');
$action = $attributes->get('action');
$icon = $attributes->get('icon') ?? $action ?? null;
$label = $attributes->get('label');

if (!$label && $action) {
    if (in_array($action, ['save', 'submit'])) $label = 'app.label.save';
    else $label = 'app.label.'.$action;
}

$element = $href ? 'a' : 'div';

$noClickAction = !$href
    && !$attributes->hasLike('wire:click*')
    && !$attributes->hasLike('x-on:click*')
    && !$attributes->hasLike('x-prompt*');

$except = ['icon', 'label', 'action', 'target', 'href', 'count'];
@endphp

@if ($action === 'share' && ($entity = $attributes->get('share')))
    <x-share>
        <x-dropdown.item icon="share" label="app.label.share" x-on:click.stop="open({
            id: {{ Js::from($entity->id) }},
            model: {{ Js::from(get_class($entity)) }},
        })"/>
    </x-share>
@elseif ($action === 'footprint' && (
    ($entity = $attributes->get('footprint'))
    || ($auditable = $attributes->get('auditable'))
))
    <x-footprint>
        <x-dropdown.item icon="shoe-prints" label="app.label.footprint" x-on:click.stop="open({{ Js::from([
            'footprint' => optional($entity)->footprintTimeline(),
            'auditable' => $auditable ?? null,
        ]) }})"/>
    </x-footprint>
@else
    <{{$element}} 
        @if ($noClickAction && $action && !in_array($action, ['delete', 'trash', 'submit']))
            wire:click="{{ str()->camel($action) }}"
        @endif

        {{ $attributes
            ->class([
                'flex items-center gap-3 cursor-pointer',
                in_array($action, ['delete', 'trash', 'remove']) ? 'text-red-500 font-medium hover:bg-red-100' : 'hover:bg-slate-50',
                $attributes->get('class', 'py-2 px-4 font-normal'),
            ])
            ->merge([
                'href' => $href,
                'rel' => $element === 'a' ? $rel : null,
                'target' => $element === 'a' ? $target : null,
                'x-prompt.delete' => $action === 'delete' ? '{ confirm: $wire.delete }' : null,
                'x-prompt.trash' => $action === 'trash' ? '{ confirm: $wire.trash }' : null,
            ])
            ->except($except) 
        }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            @if ($icon)
                <div class="shrink-0 w-4 flex">
                    <x-icon :name="$icon" class="m-auto"/>
                </div>
            @endif

            @if ($label)
                <div class="grow">{!! tr($label) !!}</div>
            @endif

            @if ($count)
                <div class="shrink-0 flex">
                    <div class="w-5 h-5 m-auto bg-sky-100 rounded-full flex items-center justify-center text-sm text-sky-700">
                        {{ $count }}
                    </div>
                </div>
            @endif
        @endif
    </{{$element}}>
@endif

