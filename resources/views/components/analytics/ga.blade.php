@if ($id = config('atom.ga_id') ?? settings('ga_id'))
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ ((array)$id)[0] }}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
@foreach ((array)$id as $gaid)
gtag('config', '{{ $gaid }}');
@endforeach
</script>
@endif

