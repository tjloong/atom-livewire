@php
$apa = $attributes->get('apa');
$type = $attributes->get('type', 'default');
$close = $attributes->get('close', false);
$title = $attributes->get('title');
$body = $attributes->get('body') ?? $attributes->get('message');
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    class="relative w-full rounded-lg border flex gap-3 {{[
        'default' => 'border-gray-200 bg-gray-100 text-gray-600',
        'info' => 'border-blue-200 bg-blue-100 text-blue-600',
        'error' => 'border-red-200 bg-red-100 text-red-600',
        'warning' => 'border-yellow-200 bg-yellow-100 text-yellow-600',
        'success' => 'border-green-200 bg-green-100 text-green-600',
    ][$type] }}">
    <div class="grow flex gap-3 p-4">
        <x-icon :name="[
            'default' => 'circle-info',
            'info' => 'circle-info',
            'error' => 'circle-xmark',
            'warning' => 'triangle-exclamation',
            'success' => 'circle-check',
        ][$type]" class="shrink-0 mt-0.5"/>
    
        @if ($slot->isNotEmpty()) {{ $slot }}
        @else
            <div class="grow flex flex-col">
            @if ($title) <h5 class="pb-1 font-medium leading-none tracking-tight text-lg">{!! $apa ? str()->apa(tr($title)) : tr($title) !!}</h5> @endif
            @if ($body) <div class="-mt-px leading-tight opacity-80">{!! tr($body) !!}</div> @endif
            </div>
        @endif
    </div>

    @if ($close)
        <div class="shrink-0 p-2">
            <div class="w-6 h-6 rounded-full flex cursor-pointer {{ [
                'default' => 'hover:bg-gray-200',
                'info' => 'hover:bg-blue-200',
                'error' => 'hover:bg-red-200',
                'warning' => 'hover:bg-yellow-200',
                'success' => 'hover:bg-green-200',
            ][$type] }}" x-on:click="show = false">
                <x-icon name="xmark" class="m-auto"/>
            </div>
        </div>
    @elseif (isset($buttons))
        <div class="shrink-0 p-2">
            {{ $buttons }}
        </div>
    @endisset
</div>
