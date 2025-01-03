<x-mail::message>
{!! $content !!}

@if ($cta)
<x-mail::button :url="get($cta, 'url')">
{!! get($cta, 'label') !!}
</x-mail::button>
@endif
</x-mail::message>
