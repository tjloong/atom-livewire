@php
$href = $attributes->get('href');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$target = $attributes->get('target', '_self');
$disabled = $attributes->get('disabled', false);
$block = $attributes->get('block', false);
$dropdown = $attributes->get('dropdown', false);
$action = $attributes->get('action');
$label = $attributes->get('label');
$nolabel = $attributes->get('no-label');
$tooltip = $attributes->get('tooltip');
$size = $attributes->size('md');
$noClickAction = !$href
    && !$dropdown
    && !in_array($action, ['google', 'facebook', 'linkedin'])
    && !$attributes->hasLike('wire:click*')
    && !$attributes->hasLike('x-on:click*')
    && !$attributes->hasLike('x-prompt*');

if (!$nolabel) {
    if (in_array($action, ['google', 'facebook', 'linkedin'])) {
        $label = $label ?? ['app.label.continue-with-social-login', ['provider' => str()->headline($action)]];
        $href = $href ?? route('socialite.redirect', ['provider' => $action, ...request()->query()]);
    }
    else if (!$label && $action) {
        if (in_array($action, ['save', 'submit'])) $label = 'app.label.save';
        else $label = 'app.label.'.$action;
    }
}

if ($tooltip === true) {
    $tooltip = $label ?? 'app.label.'.$action;
}

$element = $href ? 'a' : 'button';
$icon = $attributes->get('icon') ?? $action ?? null;
$iconsuffix = $attributes->get('icon-suffix');

$variant = $attributes->get('variant') ?? pick([
    'outline' => $attributes->get('outlined') || $attributes->get('outline'),
    'inverted' => $attributes->get('inverted') || $attributes->get('invert'),
    'ghost' => $attributes->get('ghost'),
    'invisible' => $attributes->get('invisible'),
    'default' => true,
]);

$color = $attributes->get('color') ?? pick([
    'red' => in_array($action, ['delete', 'trash', 'remove']),
    'green' => in_array($action, ['submit', 'save']),
    'google' => $action === 'google',
    'facebook' => $action === 'facebook',
    'linkedin' => $action === 'linkedin',
    'white' => true,
]);

$palette = [
    'default' => [
        'white' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
        'black' => 'bg-black text-white focus:ring-black',
        'theme' => 'bg-theme text-theme-inverted hover:bg-theme-dark focus:ring-theme',
        'green' => 'bg-green-500 text-white border-green-500 hover:bg-green-600 focus:ring-green-500',
        'red' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
        'blue' => 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500',
        'yellow' => 'bg-amber-400 text-white hover:bg-amber-600 focus:ring-amber-400',
        'gray' => 'bg-gray-200 text-gray-600 hover:bg-gray-300 focus:ring-gray-200',
        'google' => 'bg-rose-500 text-white hover:bg-rose-600 focus:ring-rose-500',
        'facebook' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-600',
        'linkedin' => 'bg-sky-600 text-white hover:bg-sky-700 focus:ring-sky-600',
    ],
    'outline' => [
        'white' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
        'black' => 'bg-white text-black border-2 border-black hover:bg-black hover:text-white focus:ring-gray-500',
        'theme' => 'bg-white text-theme border-2 border-theme hover:bg-theme hover:text-theme-light focus:ring-theme-light',
        'green' => 'bg-white text-green-500 border-2 border-green-500 hover:bg-green-500 hover:text-white focus:ring-green-200',
        'red' => 'bg-white text-red-500 border-2 border-red-500 hover:bg-red-500 hover:text-white focus:ring-red-200',
        'blue' => 'bg-white text-blue-500 border-2 border-blue-500 hover:bg-blue-500 hover:text-white focus:ring-blue-200',
        'yellow' => 'bg-white text-amber-400 border-2 border-amber-400 hover:bg-amber-400 hover:text-white focus:ring-amber-200',
        'gray' => 'bg-white text-gray-400 border-2 border-gray-200 hover:bg-gray-200 hover:text-gray-500 focus:ring-gray-100',
        'google' => 'bg-white text-rose-500 border-2 border-rose-500 hover:bg-rose-600 hover:text-white focus:ring-rose-500',
        'facebook' => 'bg-white text-blue-600 border-2 border-blue-600 hover:bg-blue-700 hover:text-white focus:ring-blue-600',
        'linkedin' => 'bg-white text-sky-600 border-2 border-sky-600 hover:bg-sky-700 hover:text-white focus:ring-sky-600',
    ],
    'inverted' => [
        'white' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
        'black' => 'bg-gray-200 text-gray-600 hover:text-white hover:bg-black focus:ring-black',
        'theme' => 'bg-theme-light text-theme hover:bg-theme hover:text-theme-inverted focus:ring-theme',
        'green' => 'bg-green-100 text-green-500 border border-green-200 hover:bg-green-500 hover:border-green-500 hover:text-white focus:ring-green-500',
        'red' => 'bg-red-100 text-red-500 border border-red-200 hover:bg-red-500 hover:border-red-500 hover:text-white focus:ring-red-500',
        'blue' => 'bg-blue-100 text-blue-500 border border-blue-200 hover:bg-blue-500 hover:border-blue-500 hover:text-white focus:ring-blue-500',
        'yellow' => 'bg-amber-100 text-amber-400 hover:bg-amber-400 hover:text-white focus:ring-amber-400',
        'gray' => 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-600 focus:ring-gray-200',
        'google' => 'bg-rose-100 text-rose-500 hover:bg-rose-500 hover:text-white focus:ring-rose-500',
        'facebook' => 'bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-600',
        'linkedin' => 'bg-sky-100 text-sky-600 hover:bg-sky-600 hover:text-white focus:ring-sky-600',
    ],
    'ghost' => [
        'white' => 'bg-transparent text-gray-800 hover:bg-white hover:border hover:border-gray-300 focus:ring-gray-300',
        'black' => 'bg-transparent text-black hover:bg-black hover:text-white hover:border hover:border-black focus:ring-black',
        'theme' => 'bg-transparent text-theme hover:bg-theme-light hover:border hover:border-theme focus:ring-theme',
        'green' => 'bg-transparent text-green-500 hover:bg-green-100 hover:border hover:border-green-200 focus:ring-green-200',
        'red' => 'bg-transparent text-red-500 hover:bg-red-100 hover:border hover:border-red-200 focus:ring-red-200',
        'blue' => 'bg-transparent text-blue-500 hover:bg-blue-100 hover:border hover:border-blue-200 focus:ring-blue-200',
        'yellow' => 'bg-transparent text-yellow-500 hover:bg-yellow-100 hover:border hover:border-yellow-200 focus:ring-yellow-200',
        'gray' => 'bg-transparent text-gray-500 hover:bg-gray-100 hover:border hover:border-gray-200 focus:ring-gray-200',
        'google' => 'bg-transparent text-rose-500 hover:bg-rose-100 hover:border hover:border-rose-300 focus:ring-rose-300',
        'facebook' => 'bg-transparent text-blue-600 hover:bg-blue-100 hover:border hover:border-blue-300 focus:ring-blue-300',
        'linkedin' => 'bg-transparent text-sky-600 hover:bg-sky-100 hover:border hover:border-sky-300 focus:ring-sky-300',
    ],
    'invisible' => [
        'white' => 'text-gray-800 hover:text-black',
        'black' => 'text-black',
        'theme' => 'text-theme hover:text-theme-dark',
        'green' => 'text-green-500 hover:text-theme-800',
        'red' => 'text-red-500 hover:text-red-800',
        'blue' => 'text-blue-500 hover:text-blue-800',
        'yellow' => 'text-yellow-500 hover:text-yellow-800',
        'gray' => 'text-gray-500 hover:text-gray-800',
        'google' => 'text-rose-500 hover:text-rose-800',
        'facebook' => 'text-blue-600 hover:text-blue-800',
        'linkedin' => 'text-sky-600 hover:text-sky-800',
    ],
][$variant];

$except = [
    '2xs', 'xs', 'sm', 'md', 'lg', 'xl', '2xl', 'invisible',
    'size', 'color', 'invert', 'inverted', 'outline', 'outlined', 'class',
    'icon', 'icon-suffix', 'label', 'block', 'recaptcha', 'dropdown', 'action',
    'no-label', 'no-action', 'wire:loading',
];
@endphp

@if ($action === 'share' && ($entity = $attributes->get('share')))
    <x-share>
        <x-button icon="share" label="app.label.share" x-on:click.stop="open({
            id: {{ Js::from($entity->id) }},
            model: {{ Js::from(get_class($entity)) }},
        })"/>
    </x-share>
@elseif ($action === 'footprint' && (
    ($entity = $attributes->get('footprint'))
    || ($auditable = $attributes->get('auditable'))
))
    <x-footprint>
        <x-button icon="shoe-prints" tooltip="app.label.footprint" no-label 
            x-on:click.stop="open({{ Js::from([
                'footprint' => optional($entity)->footprintTimeline(),
                'auditable' => $auditable ?? null,
            ]) }})">
        </x-button>
    </x-footprint>
@elseif ($dropdown && $slot->isNotEmpty())
    <x-dropdown :placement="$dropdown" :close-on-select="$attributes->get('close-on-select')">
        <x-slot:anchor>
            <x-button {{ $attributes }}/>
        </x-slot:anchor>

        {{ $slot }}
    </x-dropdown>
@else
    <{{$element}}
        @if ($tooltip)
            x-tooltip.raw="{!! tr($tooltip) !!}"
        @endif

        @if ($noClickAction && $action && !in_array($action, ['delete', 'trash', 'submit']))
            wire:click="{{ str()->camel($action) }}"
        @endif

        @if ($attributes->has('wire:loading'))
            wire:loading.class="is-loading"
        @endif

        @if ($attributes->wire('loading')->value())
            wire:target="{{ $attributes->wire('loading')->value() }}"
        @endif

        @disabled($disabled)

        {{ $attributes->class(array_filter([
            'group inline-flex rounded-md transition-colors duration-200 leading-none',
            'focus:ring-1 focus:ring-offset-1 focus:outline-none disabled:pointer-events-none disabled:opacity-60',
            $element === 'a' ? 'button' : null,
            $block ? 'w-full' : null,
            get($palette, $color),
            $icon && !$label && !$iconsuffix ? "button-icon-$size" : "button-$size",
            $variant === 'invisible' ? 'button-invisible' : null,
        ]))->only('class') }}

        {{ $attributes->merge([
            'type' => $element === 'button' ? ($action === 'submit' ? 'submit' : 'button') : null,
            'rel' => $element === 'a' ? $rel : null,
            'target' => $element === 'a' ? $target : null,
            'href' => $href,
            'x-prompt.delete' => $action === 'delete' ? '{ confirm: $wire.delete }' : null,
            'x-prompt.trash' => $action === 'trash' ? '{ confirm: $wire.trash }' : null,
        ])->except($except) }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <span class="inline-flex items-center justify-center gap-2 m-auto">
                <div class="group-[:not(.is-loading)]:hidden shrink-0 flex">
                    <svg class="animate-spin h-5 w-5 {{ $color === 'white' ? 'text-gray-400' : 'text-white' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                @if ($icon)
                    <div class="group-[.is-loading]:hidden shrink-0 flex">
                        <x-icon :name="$icon" class="m-auto"/>
                    </div>
                @endif
        
                @if ($label)
                    <div class="grow font-medium tracking-wide">
                        {!! is_array($label) ? tr(...$label) : tr($label) !!}
                    </div>
                @endif

                @if ($iconsuffix)
                    <div class="shrink-0 flex">
                        <x-icon :name="$iconsuffix" class="m-auto"/>
                    </div>
                @endif

                @if ($dropdown && $label && !$iconsuffix)
                    <div class="shrink-0 w-3 h-3 select-caret"></div>
                @endif
            </span>
        @endif
    </{{$element}}>
@endif
