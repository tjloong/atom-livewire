@php
$label = $attributes->get('label');
$inline = $attributes->get('inline');
$caption = $attributes->get('caption');
$required = $attributes->get('required');
$error = $attributes->get('error');
@endphp

<div class="group/field {{ $inline ? 'grid md:grid-cols-5' : '' }}">
    @if ($label)
        <div class="{{ $inline ? 'py-2 md:col-span-2' : 'pb-2' }}">
            <atom:_label>
                <div class="inline-flex items-center justify-center gap-2">
                    @t($label)
                    @if ($required)
                        <atom:icon asterisk size="12" class="text-red-500 shrink-0"/>
                    @endif
                </div>

                @isset ($actions)
                    <x-slot:actions>
                        {{ $actions }}
                    </x-slot:actions>
                @endisset
            </atom:_label>
        </div>
    @endif

    <div class="space-y-2 {{ $inline ? 'md:col-span-3' : '' }}">
        {{ $slot }}

        @if ($error)
            <atom:_error>@t($error)</atom:_error>
        @endif

        @if ($caption)
            <atom:caption>@t($caption)</atom:caption>
        @endif
    </div>
</div>
