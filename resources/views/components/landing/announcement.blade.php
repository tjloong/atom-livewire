@props([
    'delay' => 2500,
    'announcements' => collect(settings('announcements'))->where('is_active', true)->toArray(),
])

@if ($count = count($announcements))
    <div x-data="{
        count: @js($count),
        init () {
            new Swiper(this.$el, {
                enabled: this.count > 1,
                loop: true,
                effect: 'fade',
                autoplay: { 
                    delay: @js($delay),
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
            })
        },
    }" class="swiper">
        <div class="swiper-wrapper">
            @foreach ($announcements as $i => $announcement)
                <div class="swiper-slide">
                    <div {{ $attributes->class([
                        'p-4',
                        $attributes->get('class', 'bg-theme text-theme-inverted'),
                    ])->only('class') }}">
                        <div class="max-w-screen-xl mx-auto flex gap-3 items-center">
                            <div class="shrink-0 flex">
                                <x-icon name="bullhorn" class="m-auto" size="18"/>
                            </div>
                            <div class="grid text-lg font-medium">
                                @php $type = data_get($announcement, 'type') @endphp
                                @php $title = html_excerpt(data_get($announcement, 'title')) @endphp

                                @if ($type === 'popup')
                                    <div class="cursor-pointer truncate" x-on:click="$dispatch(@js('announcement-'.data_get($announcement, 'uuid').'-open'))">
                                        {{ $title }}
                                    </div>
                                @elseif ($type === 'link' && ($href = data_get($announcement, 'href')))
                                    <a href="{{ $href }}" target="_blank" class="truncate text-current">
                                        {{ $title }}
                                    </a>
                                @else
                                    <span class="truncate">
                                        {{ $title }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @foreach ($announcements as $i => $announcement)
        <x-modal :id="'announcement-'.data_get($announcement, 'uuid')" :header="data_get($announcement, 'title')">
            <div class="p-4 prose max-w-none">
                {!! data_get($announcement, 'content') !!}
            </div>

            @if ($href = data_get($announcement, 'href') ?? data_get($announcement, 'url'))
                <x-slot:foot>
                    <a href="{{ $href }}" target="_blank">
                        {{ data_get($announcement, 'cta') ?? $href }}
                    </a>
                </x-slot:foot>
            @endif
        </x-modal>
    @endforeach
@endif
