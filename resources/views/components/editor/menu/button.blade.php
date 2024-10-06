<div
    x-tooltip="{{ js(t($attributes->get('label'))) }}"
    {{ $attributes->class(['editor-menu-button'])->except('label') }}>
    {{ $slot }}
</div>