@php
$visible = $attributes->get('visible', false);
@endphp

<div
    x-data="{ visible: @js($visible) }"
    class="group/collapse"
    data-atom-collapse>
    <div
        x-on:click.stop="visible = !visible"
        data-atom-collapse-trigger>
        {{ $slot }}
    </div>

    @isset ($content)
        <div
            x-show="visible"
            x-collapse
            data-atom-collapse-content
            {{ $content->attributes->class(['my-1']) }}>
            {{ $content }}
        </div>
    @endisset
</div>