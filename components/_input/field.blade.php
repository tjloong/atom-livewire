@php
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$required = $attributes->get('required');
$error = $attributes->get('error');
@endphp

<atom:_field>
    @if ($label)
        <atom:_label>
            <div class="inline-flex items-center justify-center gap-2">
                @t($label)
                @if ($required)
                    <atom:icon asterisk size="12" class="text-red-500 shrink-0"/>
                @endif
            </div>
        </atom:_label>
    @endif

    {{ $slot }}

    <atom:_error>@t($error)</atom:_error>
    <atom:caption>@t($caption)</atom:caption>
</atom:_field>
