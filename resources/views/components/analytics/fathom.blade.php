@if ($id = config('atom.fathom_id') ?? settings('fathom_id'))
<script src="https://cdn.usefathom.com/script.js" data-site="{{ $id }}" defer></script>
@endif