@php
$user = $attributes->get('user') ?? user();
$size = $attributes->get('size');
$avatar = get($user, 'avatar.url') ?? get($user, 'avatar') ?? $attributes->get('avatar');
$name = get($user, 'name');
$short = preg_replace('/\s+/', '', $name);
$short = $size === 'lg' ? substr($short, 0, 2) : substr($short, 0, 1);

$classes = $attributes->classes()
    ->add('flex items-center justify-center gap-2 w-full')
    ->add(match ($size) {
        'lg' => '[&>[data-atom-profile-avatar]]:w-14 [&>[data-atom-profile-avatar]]:h-14 [&>[data-atom-profile-avatar]]:text-lg',
        'sm' => '[&>[data-atom-profile-avatar]]:w-6 [&>[data-atom-profile-avatar]]:h-6 [&>[data-atom-profile-avatar]]:text-sm',
        default => '[&>[data-atom-profile-avatar]]:w-8 [&>[data-atom-profile-avatar]]:h-8',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['user', 'size', 'avatar'])
    ;
@endphp

<div {{ $attrs }} data-atom-profile>
    <div
        class="shrink-0 rounded-md bg-zinc-100 border shadow-sm flex items-center justify-center"
        data-atom-profile-avatar>
        @if ($avatar)
            <img src="{{ $avatar }}" class="w-full h-full object-cover">
        @else
            <span class="font-medium text-zinc-500 uppercase">
                {{ $short }}
            </span>
        @endif
    </div>

    <div class="grow grid gap-1">
        <div class="font-medium truncate">
            {{ $name }}
        </div>
    </div>

    <div class="shrink-0 items-center justify-center text-zinc-400 hidden [[data-atom-dropdown-trigger]>&]:flex">
        <x-icon down/>
    </div>
</div>