<div
    x-tooltip.raw="{{ tr($attributes->get('label')) }}"
    {{ $attributes->class(['editor-menu-button'])->except('label') }}>
    {{ $slot }}
</div>