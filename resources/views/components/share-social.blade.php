@php
// refer https://ellisonleao.github.io/sharer.js for available sites
$sites = $attributes->get('sites', [
    'facebook',
    'twitter',
    'linkedin',
    'whatsapp',
    'telegram',
    'email',
]);

$icons = [
    'facebook' => ['name' => 'brands facebook', 'color' => 'text-blue-500'],
    'twitter' => ['name' => 'brands twitter', 'color' => 'text-blue-400'],
    'linkedin' => ['name' => 'brands linkedin', 'color' => 'text-blue-400'],
    'whatsapp' => ['name' => 'brands whatsapp', 'color' => 'text-green-500'],
    'telegram' => ['name' => 'brands telegram', 'color' => 'text-blue-500'],
    'email' => ['name' => 'envelope', 'type' => 'regular'],
];

$url = $attributes->get('url');
$title = $attributes->get('title');
$label = $attributes->get('label', 'app.label.share-to');
$nolabel = $attributes->get('no-label');
@endphp

<div>
    @if (!$nolabel)
        <div class="mb-2">
            <x-label :label="$label"/>
        </div>
    @endif

    <div x-data x-init="Sharer.init()" class="flex items-center gap-2 flex-wrap">
        @foreach ($sites as $site)
            <div
                data-sharer="{{ $site }}"
                data-url="{!! $url !!}"
                data-title="{!! $title !!}"
                x-tooltip.raw="{{ (string) str($site)->headline() }}"
                class="w-10 h-10 rounded flex text-2xl cursor-pointer hover:bg-slate-100 hover:border">
                <x-icon :name="get($icons, $site.'.name')" class="m-auto {{ get($icons, $site.'.color') }}"/>
            </div>
        @endforeach
    
        <div
            x-tooltip.raw="{{ tr('app.label.copy-link') }}"
            x-on:click.stop="$clipboard({{ Js::from($url) }})"
            class="w-10 h-10 rounded flex text-lg cursor-pointer hover:bg-slate-100 hover:border">
            <x-icon name="link" class="m-auto"/>
        </div>
    </div>
</div>

