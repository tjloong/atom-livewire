@php
    $delay = 5000;
    $announcements = model('announcements')->status('PUBLISHED')->get();
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
                style="background-color: {{ data_get($row->data, 'bg_color') }};">
                <div class="max-w-screen-xl mx-auto text-center">
                    @if ($href = $row->href)
                        <a href="{{ $href }}" target="_blank" style="color: {{ data_get($row->data, 'text_color') }}">
                            {!! $row->name !!}
                        </a>
                    @elseif ($row->content)
                        <a href="{{ route('web.announcement', $row->slug) }}" style="color: {{ data_get($row->data, 'text_color') }}">
                            {!! $row->name !!}
                        </a>
                    @else
                        <div style="color: {{ data_get($row->data, 'text_color') }}">
                            {!! $row->name !!}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
