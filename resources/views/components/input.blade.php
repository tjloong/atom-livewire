@php
$field = $attributes->get('for') ?? $attributes->get('field') ?? $attributes->wire('model')->value();
$size = $attributes->get('size');
$icon = $attributes->get('icon');
$type = $attributes->get('type');
$label = $attributes->get('label');
$readonly = $attributes->get('readonly', false);
$disabled = $attributes->get('disabled', false);
$nolabel = $attributes->get('no-label');
$transparent = $attributes->get('transparent');
$placeholder = $attributes->get('placeholder');

$size = $attributes->get('size') ?? pick([
    '2xs' => $attributes->get('2xs'),
    'xs' => $attributes->get('xs'),
    'sm' => $attributes->get('sm'),
    'lg' => $attributes->get('lg'),
    'xl' => $attributes->get('xl'),
    '2xl' => $attributes->get('2xl'),
    'md' => true,
]);

$size = [
    '2xs' => 'text-[9px] h-5',
    'xs' => 'text-xs h-6',
    'sm' => 'text-sm h-8',
    'md' => 'text-base h-10',
    'lg' => 'text-lg font-medium h-12',
    'xl' => 'text-xl font-semibold h-[4rem]',
    '2xl' => 'text-2xl font-semibold h-[4rem]',
][$size];

$except = [
    'for', 'field', 'size', 'icon', 'type', 'label', 'no-label', 'transparent', 'placeholder',
    '2xs', 'xs', 'sm', 'md', 'lg', 'xl', '2xl', 'readonly', 'disabled',
];
@endphp

<div>
    @if (!$nolabel)
        <div class="mb-2">
            <x-label :label="$label" :for="$field"/>
        </div>
    @endif

    <span {{ $attributes->class(array_filter([
        'inline-block leading-normal',
        $transparent
            ? 'bg-transparent has-[:focus]:border-b-2 has-[:focus]:border-gray-300 has-[:focus]:border-dashed'
            : 'px-2 bg-white border border-gray-300 rounded-md has-[:focus]:ring-1 has-[:focus]:ring-theme has-[:focus]:ring-offset-1 hover:ring-1 hover:ring-gray-200',
        $disabled ? 'opacity-50' : null,
        $size,
        $attributes->get('class'),
    ]))->only('class') }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @endif
    </span>
</div>
