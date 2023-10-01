@php
    $user = $attributes->get('user');
    $getSize = function() use ($attributes) {
        return collect([
            'sm' => $attributes->get('sm'),
            'md' => $attributes->get('md'),
            'lg' => $attributes->get('lg'),
            'xl' => $attributes->get('xl'),
        ])->filter()->keys()->first() ?? 'md';
    };
@endphp

<div {{ $attributes->except(['user', 'sm', 'md', 'lg', 'xl', 'avatar', 'name', 'email']) }}>
    <div class="flex items-center gap-3">
        <div class="shrink-0">
            <x-image avatar 
                :size="[
                    'sm' => '32x32',
                    'md' => '40x40',
                    'lg' => '64x64',
                    'xl' => '128x128',
                ][$getSize()]"
                :src="optional($user->avatar)->url ?? $attributes->get('avatar')"
                :placeholder="$user->name ?? $attributes->get('name')"/>
        </div>
    
        <div class="grow">
            <div class="font-medium {{ [
                'sm' => 'text-sm',
                'md' => 'text-base',
                'lg' => 'text-lg',
                'xl' => 'text-xl',
            ][$getSize()] }}">{{ $user->name ?? $attributes->get('name') }}</div>
    
            <div class="text-gray-500 {{
                [
                    'sm' => 'text-sm',
                    'md' => 'text-sm',
                    'lg' => 'text-base',
                    'xl' => 'text-lg',
                ][$getSize()]
            }}">{{ $user->email ?? $attributes->get('email') }}</div>
        </div>
    </div>
</div>