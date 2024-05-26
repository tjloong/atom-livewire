@php
    $clear = $attributes->get('clear', false);
    $readonly = $attributes->get('readonly');
    $transparent = $attributes->get('transparent');
    $placeholder = $attributes->get('placeholder');
    $icon = $attributes->get('icon');
    $except = ['error', 'required', 'class', 'caption', 'label', 'label-tag', 'prefix', 'postfix', 'suffix'];
@endphp

<x-form.field {{ $attributes->except('class') }}>
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
        {{ $attributes->class([
            'flex items-center gap-2',
            $readonly ? 'readonly' : '',
            $transparent ? 'transparent' : '',
            $attributes->get('class', 'form-input w-full'),
        ])->only(['class', 'wire:clear', 'wire:clear.stop', 'x-on:clear', 'x-on:clear.stop']) }}>
        @if ($icon)
            <div class="shrink-0 flex">
                <x-icon :name="$icon" class="m-auto text-gray-400"/>
            </div>
        @endif

        @isset($prefix) {{ $prefix }}
        @elseif ($prefix = $attributes->get('prefix'))
            <div class="shrink-0 text-gray-500 font-medium">{{ tr($prefix) }}</div>
        @endisset

        <div class="grow">
            <input type="text" 
                x-ref="input"
                x-on:focus="focus = true"
                x-on:blur="focus = false"
                x-on:input="clearable = !empty($event.target.value)"
                class="w-full transparent"
                placeholder="{{ $placeholder === 'autogen' ? tr('app.label.empty-autogen') : tr($placeholder) }}"
                {{ $attributes->except($except) }}>
        </div>

        @if ($attributes->hasLike('wire:clear*', 'x-on:clear*'))
            <div x-show="clearable" class="shrink-0">
                <x-close x-on:click.stop="clear()" class="-m-1"/>
            </div>
        @endif

        @isset($suffix) {{ $suffix }}
        @elseif ($suffix = $attributes->get('suffix') ?? $attributes->get('postfix'))
            <div class="shrink-0 text-gray-500 font-medium">{{ tr($suffix) }}</div>
        @endisset

        @isset($button)
            @php $label = $button->attributes->get('label') @endphp 
            @php $icon = $button->attributes->get('icon') @endphp 
            <button type="button" {{ $button->attributes->class([
                'shrink-0 flex items-center gap-1 px-3 -mx-3 py-1.5 -my-1.5 text-sm',
                $button->attributes->get('class', 'bg-white border-l hover:bg-gray-100'),
            ]) }}>
                @if ($icon)
                    <div class="shrink-0 flex">
                        <x-icon :name="$icon" class="m-auto"/>
                    </div>
                @endif

                {!! tr($label) !!}
            </button>
        @endisset
    </div>
</x-form.field>
