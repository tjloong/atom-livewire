@php
$icon = $attributes->get('icon', 'empty');
$size = $attributes->get('size');
@endphp

@if ($size === 'sm')
    <div class="flex items-center justify-center w-full">
        <div class="flex justify-center gap-3 py-5">
            <div class="shrink-0 flex justify-center text-gray-400">
                <x-icon :name="$icon" size="24"/>
            </div>

            <div class="grow self-center">
                @if ($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    <div class="space-y-3">
                        <atom:_heading>@t('no-results')</atom:_heading>
                        <atom:subheading>@t('we-could-not-find-anything')</atom:subheading>
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col items-center justify-center gap-3 py-8">
        <div class="text-gray-300">
            <x-icon :name="$icon" size="40"/>
        </div>

        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <div class="flex flex-col items-center justify-center">
                <atom:_heading size="lg">@t('no-results')</atom:_heading>
                <atom:subheading>@t('we-could-not-find-anything')</atom:subheading>
            </div>
        @endif
    </div>
@endif
