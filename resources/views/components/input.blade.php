@php
$field = $attributes->field();
$inline = $attributes->get('inline', false);
$size = $attributes->get('size');
$icon = $attributes->get('icon');
$type = $attributes->get('type', 'text');
$label = $attributes->get('label');
$prefix = $prefix ?? $attributes->get('prefix');
$suffix = $suffix ?? $attributes->get('suffix');
$caption = $caption ?? $attributes->get('caption');
$error = $error ?? $attributes->get('error');
$transparent = $attributes->get('transparent');
$placeholder = $attributes->get('placeholder');
$readonly = $attributes->get('readonly');
$disabled = $attributes->get('disabled');
$size = $attributes->size('md');
@endphp

<x-field :class="$inline ? 'items-center' : null" :attributes="$attributes->merge([
    'field' => $field,
    'inline' => $inline,
])->only(['inline', 'field', 'for', 'no-label', 'label', 'required'])">
    <span {{ $attributes
        ->class(array_filter([
            "inline-block leading-normal w-full input-$size has-[:disabled]:opacity-50",
            'group/input group-has-[.error]/field:border-red-500 group-has-[.error]/field:ring-red-300',

            $transparent
                ? 'bg-transparent rounded-md border-dashed transition-all hover:border hover:border-gray-200 hover:px-2'
                : 'bg-white border border-gray-300 rounded-md hover:ring-1 hover:ring-gray-200',

            $transparent
                ? 'has-[:focus]:bg-white has-[:focus]:px-2 has-[:focus]:border has-[:focus]:border-gray-300 has-[input:readonly]:border-0'
                : 'has-[:focus]:ring-1 has-[:focus]:ring-theme has-[:focus]:ring-offset-1 has-[input:read-only]:ring-0',

            $slot->isEmpty() && in_array($type, ['text', 'number', 'email', 'password']) ? 'h-px' : null,
        ]))
        ->only(['class', 'x-ref'])
    }}>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @elseif (in_array($type, ['text', 'number', 'email', 'password']))
        <span
            x-data="{ clearable: false }"
            x-effect="$nextTick(() => clearable = !empty($refs.input.value))"
            x-on:input="$nextTick(() => clearable = !empty($refs.input.value))"
            class="inline-flex items-center gap-2 w-full h-full {{ !$transparent ? 'px-3' : 'px-1.5' }}"
            {{ $attributes->only(['wire:clear', 'wire:key', 'x-on:clear']) }}>
            @if ($icon)
                <div class="shrink-0 text-gray-300">
                    <x-icon :name="$icon"/>
                </div>
            @endif

            @if ($prefix instanceof \Illuminate\View\ComponentSlot)
                {{ $prefix }}
            @elseif ($prefix)
                <div class="shrink-0 text-gray-500 font-medium">{!! tr($prefix) !!}</div>
            @endif

            <input
                type="{{ $type }}"
                x-ref="input"
                placeholder="{!! $placeholder === true ? tr($label) : tr($placeholder) !!}"
                class="grow appearance-none no-spinner w-full bg-transparent peer group-[.text-right]/input:text-right group-[.text-center]/input:text-center"
                {{ $attributes->whereStartsWith('wire:')->except('wire:key') }}
                {{ $attributes->whereStartsWith('x-') }}
                {{ 
                    $attributes->when(true, fn($attr) => $attr->merge([
                        'step' => 'any',
                    ]))->only([
                        'min',
                        'max',
                        'step', 
                        'maxlength',
                        'value', 
                        'autofocus', 
                        'disabled', 
                        'readonly',
                    ])
                }}>

            @if($type !== 'number' && !$readonly && !$disabled)
                <div x-show="clearable" x-on:click.stop="() => {
                    $refs.input.value = null
                    $refs.input.dispatchEvent(new Event('input', { bubbles: true }))
                    $nextTick(() => {
                        $dispatch('clear')
                        $refs.input.focus()
                    })
                }" class="shrink-0 text-gray-400 cursor-pointer hover:text-black px-1 hidden group-hover/input:block peer-focus:block peer-disabled:hidden peer-read-only:hidden">
                    <x-icon name="xmark"/>
                </div>
            @endif

            @if($suffix instanceof \Illuminate\View\ComponentSlot)
                {{ $suffix }}
            @elseif ($suffix)
                <div class="shrink-0 text-gray-500 font-medium">{!! tr($suffix) !!}</div>
            @endif

            @if ($type === 'password')
                <div
                    x-show="clearable"
                    x-on:click="() => {
                        let type = $refs.input.getAttribute('type')
                        $refs.input.setAttribute('type', type === 'password' ? 'text' : 'password')
                    }"
                    class="shrink-0 cursor-pointer text-gray-400 hover:text-black px-1 peer-disabled:hidden peer-read-only:hidden">
                    <x-icon name="eye"/>
                </div>
            @endif

            @if(isset($button) && $button instanceof \Illuminate\View\ComponentSlot)
                <div class="shrink-0" style="margin-right: -12px;">
                    @if ($button->isNotEmpty())
                        {{ $button }}
                    @else
                        <x-button
                            class="rounded-l-none"
                            :size="$size"
                            :attributes="$button->attributes->except('size')">
                        </x-button>
                    @endif
                </div>
            @endif
        </span>
    @endif
    </span>

    @if ($caption)
        <div class="mt-2">
            @if ($caption instanceof \Illuminate\View\ComponentSlot)
                {{ $caption }}
            @else
                <div class="text-sm text-gray-500">
                    {!! tr($caption) !!}
                </div>
            @endif
        </div>
    @endif

    @if ($field)
        <x-error :field="$field" class="mt-2"/>
    @elseif ($error instanceof \Illuminate\View\ComponentSlot)
        {{ $error }}
    @elseif ($error)
        <x-error :label="$error" class="mt-2"/>
    @endif
</x-field>
