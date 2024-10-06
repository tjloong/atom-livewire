@php
// refer https://ellisonleao.github.io/sharer.js for available sites
$sites = $attributes->get('sites', [
    'facebook',
    'twitter-x',
    'linkedin',
    'whatsapp',
    'telegram',
    'email',
]);

$url = $attributes->get('url');
$title = $attributes->get('title');
@endphp

<div>
    <div class="text-sm text-zinc-400 font-medium mb-2">
        @t('share-to')
    </div>

    <div x-data x-init="Sharer.init()" class="flex items-center gap-2 flex-wrap">
        @foreach ($sites as $site)
            <div
                data-sharer="{{ $site }}"
                data-url="{!! $url !!}"
                data-title="{!! $title !!}"
                x-tooltip="{{ js((string) str($site)->headline()) }}"
                class="w-10 h-10 rounded flex text-2xl cursor-pointer hover:bg-slate-100 hover:border">
                <atom:icon :name="$site" size="24" class="m-auto {{
                    match ($site) {
                        'facebook' => 'text-blue-500',
                        'twitter-x' => 'text-black',
                        'linkedin' => 'text-blue-400',
                        'whatsapp' => 'text-green-500',
                        'telegram' => 'text-blue-500',
                        default => 'text-zinc-800',
                    }
                }}"/>
            </div>
        @endforeach
    
        <div
            x-tooltip="{{ js(t('copy-link')) }}"
            x-on:click.stop="$clipboard({{ js($url) }})"
            class="w-10 h-10 rounded flex text-lg cursor-pointer hover:bg-slate-100 hover:border">
            <atom:icon link size="24" class="m-auto"/>
        </div>
    </div>
</div>

