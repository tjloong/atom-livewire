@php
    $prefix = $prefix ?? $attributes->get('prefix');
    $postfix = $postfix ?? $attributes->get('postfix');
    $clear = $attributes->get('clear', false);
    $placeholder = $attributes->get('placeholder');
    $icon = $attributes->get('icon');
@endphp

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            focus: false,
            clearable: false,
            clear () {
                this.$refs.input.value = null
                this.clearable = false
                this.$dispatch('clear')
            },
        }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ $attributes->get('readonly') ? 'readonly' : '' }}"
        {{ $attributes->wire('clear') }}
        {{ $attributes->whereStartsWith('x-on:clear') }}>
        @if ($icon) <div class="shrink-0"><x-icon :name="$icon" class="text-gray-400"/></div>@endif

        @if (is_string($prefix))
            @if (str($prefix)->is('icon:*')) <x-icon :name="str($prefix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ tr($prefix) }}</div>
            @endif
        @else {{ $prefix }}
        @endif

        <div class="grow">
            <input type="text" 
                x-ref="input"
                x-on:focus="focus = true"
                x-on:blur="focus = false"
                x-on:input="clearable = !empty($event.target.value)"
                class="w-full transparent"
                placeholder="{{ $placeholder === 'autogen' ? tr('common.label.empty-autogen') : tr($placeholder) }}"
                {{ $attributes->except(['error', 'required', 'caption', 'label', 'label-tag', 'prefix', 'postfix']) }}>
        </div>

        @if ($attributes->hasLike('wire:clear*', 'x-on:clear*'))
            <div x-show="clearable" class="shrink-0">
                <x-close x-on:click.stop="clear()" class="-m-1"/>
            </div>
        @endif

        @if (is_string($postfix))
            @if (str($postfix)->is('icon:*')) <x-icon :name="str($postfix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ tr($postfix) }}</div>
            @endif
        @elseif ($postfix)
            {{ $postfix }}
        @endif

        @isset($button)
            @php $label = $button->attributes->get('label') @endphp 
            @php $icon = $button->attributes->get('icon') @endphp 
            <a {{ $button->attributes->class([
                'flex items-center justify-center gap-2 rounded-full -mr-1 text-sm',
                $label ? 'px-2 py-0.5' : null,
                !$label && $icon ? 'p-1' : null,
                $button->attributes->get('class', 'text-gray-800 bg-gray-200'),
            ]) }}">
                @if ($icon) <x-icon :name="$icon" size="11"/> @endif
                {{ tr($label) }}
            </a>
        @endisset
    </div>
</x-form.field>
