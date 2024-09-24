@php
$apa = $attributes->get('apa');
$icon = $attributes->get('icon', 'empty');
$title = $attributes->get('title', 'app.label.no-results');
$message = $attributes->get('message') ?? $attributes->get('subtitle') ?? 'app.label.we-could-not-find-anything';
$size = $attributes->size('md');
@endphp

@if ($size === 'sm')
    <div class="flex items-center justify-center w-full">
        <div class="flex justify-center gap-3 py-5">
            <div class="shrink-0 flex justify-center text-gray-400">
                <x-icon :name="$icon" size="24"/>
            </div>

            <div class="grow flex flex-col gap-3 self-center">
                <div>
                    <div class="font-semibold">
                        {!! $apa ? str()->apa(tr($title)) : tr($title) !!}
                    </div>

                    @if ($message)
                        <div class="text-gray-400 font-medium text-sm">
                            {!! tr($message) !!}
                        </div> 
                    @endif
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
@else
    <div class="flex flex-col items-center justify-center gap-3 py-8">
        <div class="text-gray-300">
            <x-icon :name="$icon" size="45"/>
        </div>

        <div class="flex flex-col items-center justify-center">
            <div class="text-lg font-semibold">
                {!! $apa ? str()->apa(tr($title)) : tr($title) !!}
            </div>

            @if ($message)
                <div class="text-gray-400 font-medium">
                    {!! tr($message) !!}
                </div> 
            @endif
        </div>

        {{ $slot }}
    </div>
@endif
