@php
$field = $attributes->get('for') ?? $attributes->get('field') ?? $attributes->wire('model')->value();
$size = $attributes->get('size');
$icon = $attributes->get('icon');
$type = $attributes->get('type');
$label = $attributes->get('label');
$nolabel = $attributes->get('no-label');
$transparent = $attributes->get('transparent');
$placeholder = $attributes->get('placeholder');
$size = $attributes->size('md');
@endphp

<div>
    @if (!$nolabel)
        <div class="mb-2">
            <x-label :label="$label" :for="$field"/>
        </div>
    @endif

    <span {{ $attributes
        ->class(array_filter([
            "inline-block leading-normal input-$size has-[:disabled]:opacity-50",

            $transparent
                ? 'bg-transparent'
                : 'px-2 bg-white border border-gray-300 rounded-md hover:ring-1 hover:ring-gray-200',

            $transparent
                ? 'has-[:focus]:border-b-2 has-[:focus]:border-gray-300 has-[:focus]:border-dashed has-[:readonly]:border-0'
                : 'has-[:focus]:ring-1 has-[:focus]:ring-theme has-[:focus]:ring-offset-1 has-[:read-only]:ring-0',

            $attributes->get('class'),
        ]))
        ->only('class')
    }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @endif
    </span>
</div>
