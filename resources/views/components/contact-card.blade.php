@php
    $href = $attributes->get('href');
    $contact = $attributes->get('contact');
    $getSize = function() use ($attributes) {
        return collect([
            'xs' => $attributes->get('xs'),
            'sm' => $attributes->get('sm'),
            'md' => $attributes->get('md'),
            'lg' => $attributes->get('lg'),
            'xl' => $attributes->get('xl'),
        ])->filter()->keys()->first() ?? 'md';
    };
@endphp

<div 
    @if ($href)
        x-data
        x-on:click="window.location = @js($href)"
    @endif
    {{ $attributes
        ->class([
            $attributes->get('class') ?? (
                $getSize() === 'xs' ? 'border border-gray-300 rounded-full p-0.5 pr-2' : ''
            ),
            $attributes->hasLike('href', '*click*') ? 'cursor-pointer' : '',
        ])
        ->except(['contact', 'sm', 'md', 'lg', 'xl', 'avatar', 'name', 'email']) }}>
    <div class="flex items-center gap-2 w-full">
        <div class="shrink-0">
            <x-image avatar 
                :size="[
                    'xs' => '24x24',
                    'sm' => '32x32',
                    'md' => '40x40',
                    'lg' => '64x64',
                    'xl' => '128x128',
                ][$getSize()]"
                :src="data_get($contact, 'avatar.url') ?? data_get($contact, 'avatar')"
                :placeholder="data_get($contact, 'name')"/>
        </div>
    
        <div class="grow flex flex-col w-full">
            <div class="font-medium truncate {{ [
                'xs' => 'text-sm',
                'sm' => 'text-sm',
                'md' => 'text-base',
                'lg' => 'text-lg',
                'xl' => 'text-xl',
            ][$getSize()] }}">{{ data_get($contact, 'name') }}</div>

            <div class="text-gray-500 truncate {{
                [
                    'xs' => 'text-xs',
                    'sm' => 'text-sm',
                    'md' => 'text-sm',
                    'lg' => 'text-base',
                    'xl' => 'text-lg',
                ][$getSize()]
            }}">{{ data_get($contact, 'email') }}</div>
        </div>
    </div>
</div>