@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<table style="width: 100%">
<tr>
<td style="vertical-align: middle;">
@foreach ((array)($logo ?? asset('storage/img/logo.png')) as $val)
<div style="
    margin: 1.5rem 0.5rem;
    width: {{ data_get($val, 'width', '200px') }};
    height: {{ data_get($val, 'height', '100px') }};
    display: inline-block;
">
<img 
    src="{{ is_string($val) ? $val : data_get($val, 'url') }}"
    alt="{{ data_get($val, 'alt') ?? config('app.name') }}"
    style="width: 100%; height: 100%; object-fit: contain;"
>
</div>
@endforeach
</td>
</tr>
</table>
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
@isset($footer)
{{ $footer }}
@else
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endisset
@endcomponent
@endslot
@endcomponent
