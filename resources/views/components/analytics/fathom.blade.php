@if ($id = config('atom.analytics.fathom_id') ?? settings('analytics.fathom_id'))
<script src="https://cdn.usefathom.com/script.js" data-site="{{ $id }}" defer></script>
@endif