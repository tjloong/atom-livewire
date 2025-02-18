@php
$visible = $attributes->get('visible', false);
@endphp

<div x-data="{ visible: @js($visible) }" class="group/collapse" data-atom-collapse>
    <div
        x-on:click.stop="visible = !visible"
        x-bind:data-atom-collapse-trigger-visible="visible"
        x-bind:data-atom-collapse-trigger-invisible="!visible"
        data-atom-collapse-trigger>
        {{ $slot }}
    </div>

    @isset ($content)
        <div x-show="visible" x-collapse data-atom-collapse-content {{ $content->attributes->class(['mt-1']) }}>
            {{ $content }}
        </div>
    @endisset
</div>