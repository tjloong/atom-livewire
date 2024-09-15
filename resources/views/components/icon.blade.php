@php
$icons = [
    ...(
        json_decode(file_get_contents(atom_path('resources/json/icons.json')), true)
    ),
    ...(
        file_exists(resource_path('json/icons.json'))
        ? json_decode(file_get_contents(resource_path('json/icons.json')), true)
        : []
    ),
];

$alias = [
    'accounts' => 'address-book',
    'add' => 'plus',
    'address' => 'location-dot',
    'analytics' => 'chart-simple',
    'archive' => 'box-archive',
    'article' => 'feather-pointed',
    'assign' => 'plus',
    'attach' => 'paperclip',
    'attachment' => 'paperclip',
    'back' => 'arrow-left',
    'billing' => 'credit-card',
    'block' => 'ban',
    'blog' => 'feather-pointed',
    'buy' => 'bag-shopping',
    'cancel' => 'ban',
    'client' => 'address-book',
    'close' => 'xmark',
    'company' => 'building',
    'config' => 'gear',
    'contacts' => 'address-book',
    'create' => 'plus',
    'delete' => 'trash-can',
    'deselect-all' => 'xmark',
    'download' => 'circle-down',
    'duplicate' => 'copy',
    'edit' => 'pen-to-square',
    'email' => 'envelope',
    'enquiries' => 'clipboard-question',
    'enquiry' => 'clipboard-question',
    'excel' => 'file-excel',
    'export' => 'file-export',
    'facebook' => 'brands facebook',
    'finance' => 'magnifying-glass-dollar',
    'financial' => 'magnifying-glass-dollar',
    'google' => 'brands google',
    'help' => 'life-ring',
    'hide' => 'eye-slash',
    'import' => 'cloud-arrow-up',
    'invitation' => 'envelope-circle-check',
    'invoice' => 'file-invoice-dollar',
    'linkedin' => 'brands linkedin',
    'location' => 'location-dot',
    'login' => 'arrow-right-to-bracket',
    'logout' => 'arrow-right-from-bracket',
    'new' => 'plus',
    'open' => 'arrow-up-right-from-square',
    'page-back' => 'arrow-left',
    'powerpoint' => 'file-powerpoint',
    'ppt' => 'file-powerpoint',
    'preference' => 'screwdriver-wrench',
    'preview' => 'eye',
    'product' => 'cube',
    'project' => 'survey',
    'publish' => 'cloud-arrow-up',
    'purchase' => 'bag-shopping',
    'quotation' =>  'file-circle-question',
    'refresh' => 'arrows-rotate',
    'remove' => 'circle-minus',
    'report' => 'magnifying-glass-chart',
    'restore' => 'trash-arrow-up',
    'sales' => 'sack-dollar',
    'save' => 'check',
    'search' => 'magnifying-glass',
    'select-all' => 'check-double',
    'send' => 'paper-plane',
    'seo' => 'searchengin',
    'setting' => 'gear',
    'share' => 'share-nodes',
    'show' => 'eye',
    'signup' => 'user-plus',
    'split' => 'arrows-split-up-and-left',
    'stop' => 'ban',
    'submit' => 'check',
    'support' => 'life-ring',
    'telegram' => 'brands telegram',
    'tenant' => 'house-user',
    'transaction' => 'arrow-right-arrow-left',
    'trash' => 'trash-can',
    'unblock' => 'play',
    'undo' => 'arrow-rotate-left',
    'unpublish' => 'arrow-rotate-left',
    'update' => 'pen-to-square',
    'upload' => 'cloud-arrow-up',
    'vendor' => 'people-carry-box',
    'whatsapp' => 'brands whatsapp',
    'word' => 'file-word',
];

$name = $attributes->get('name')
    ?? collect($attributes->whereDoesntStartWith('x-', 'wire:')->getAttributes())
        ->keys()
        ->reject(fn($key) => in_array($key, ['class', 'style', 'size']))
        ->first();

$name = get($alias, $name) ?? $name;

$icon = get($icons, $name) ?? get($alias, $name) ?? $name;

$type = pick([
    'svg' => str($icon)->startsWith('<svg'),
    'image' => str($icon)->startsWith('data:image/'),
]);

$size = $attributes->get('size', 15);
$size = $size ? (string) str($size)->finish('px') : null;

$except = ['name', 'size'];
@endphp

@if ($type === 'svg')
    <div {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center',
        'style' => "width: $size; height: $size;",
    ])->except($except) }}>
        {!! $icon !!}
    </div>
@elseif ($type === 'image')
    <div {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center',
        'style' => "width: $size; height: $size;",
    ])->except($except) }}>
        <img src="{{ $icon }}" class="w-full h-full object-contain object-center">
    </div>
@else
    <i {{ $attributes->merge([
        'class' => collect(explode(' ', $icon))
            ->map(fn($value) => (string) str($value)->start('fa-'))
            ->prepend('fa-solid')
            ->join(' '),
        'style' => "width: $size; height: $size;",
    ])->except($except) }}></i>
@endif
