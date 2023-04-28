@if ($id = config('atom.analytics.ja_id') ?? settings('analytics.ja_id'))
<script async src="https://analytics.jiannius.com/script.js" data-website-id="{{ $id }}"></script>
@endif