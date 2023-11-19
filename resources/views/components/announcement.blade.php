@php
    $delay = 5000;
    $announcements = model('announcement')->status('PUBLISHED')->get();
    $count = $announcements->count();
@endphp

@if ($announcements->count())
    <div 
        x-cloak
        x-data="{
            index: 0,
            total: @js($count - 1),
            delay: @js($delay),
            next () {
                this.index = this.index >= this.total
                    ? 0
                    : this.index + 1
            },
        }"
        x-init="setInterval(() => next(), delay)">
        @foreach ($announcements as $i => $row)
            <div
                x-show="index === @js($i)"
                x-transition:enter.duration.1000ms
                class="py-3 px-4" 
                @if ($row->bg_color) style="background-color: {{ $row->bg_color }};"> @endif
                <div class="max-w-screen-xl mx-auto text-center">
                    @if ($row->href || $row->content)
                        <a 
                            href="{{ $row->content ? route('web.announcement', $row->slug) : $row->href }}" 
                            target="_blank"
                            @if ($row->text_color) style="color: {{ $row->text_color }}" @endif>
                            {!! $row->name !!}
                        </a>
                    @else
                        <div @if ($row->text_color) style="color: {{ $row->text_color }}" @endif>
                            {!! $row->name !!}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
