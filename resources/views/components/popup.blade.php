@php
    $delay = 1000;
    $popups = model('popup')->status('PUBLISHED')->get();
    $count = $popups->count();
@endphp

@if ($count)
    <div
        x-cloak
        x-data="{
            show: false,
            index: 0,
            count: @js($count),
            delay: @js($delay),
            timout: null,
            interval: null,
            roll () {
                this.interval = setInterval(() => this.next(), this.delay)
            },
            next (index) {
                if (this.count <= 1) return
                clearTimeout(this.timout)
                this.hide()
                this.timout = setTimeout(() => this.reveal(index), 500)
            },
            hide () {
                this.$refs.slides.classList.add('opacity-0')
                this.$refs.slides.classList.add('duration-500')
            },
            reveal (index) {
                this.index = index || (this.index >= this.count -1 ? 0 : this.index + 1)
                this.$refs.slides.classList.remove('opacity-0')
            },
        }"
        x-init="setTimeout(() => {
            show = true
            roll()
        }, delay)"
        x-show="show"
        x-on:click="show = false"
        x-transition.opacity.duration.500ms
        class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center overflow-auto py-20 px-5"
        style="z-index: 999;">
        <div 
            x-on:click.stop
            x-on:mouseenter="clearInterval(interval)"
            x-on:mouseleave="roll()"
            class="max-w-screen-lg bg-white rounded-lg shadow overflow-hidden relative">
            <div
                x-on:click="show = false"
                class="absolute top-2 right-3 text-xl text-gray-400 cursor-pointer w-8 h-8 rounded-full flex hover:bg-black hover:text-white">
                <x-icon name="xmark" class="m-auto"/>
            </div>

            <div x-ref="slides" class="transition-opacity">
                @foreach ($popups as $i => $popup)
                    <div 
                        x-show="index === @js($i)"
                        class="p-5 editor-content" 
                        style="
                            background-color: {{ $popup->bg_color }};
                            @if ($popup->image) background: url({{ $popup->image->url }}) no-repeat center center / cover; @endif
                        ">
                        {!! $popup->content !!}
                    </div>
                @endforeach
            </div>

            @if ($count > 1)
                <div class="absolute bottom-0 left-0 right-0 flex items-center justify-center gap-2 py-4">
                    @foreach(range(0, $count - 1) as $n)
                        <div 
                            x-on:click="next(@js($n))"
                            x-bind:class="index === @js($n) ? 'w-3 h-3' : 'w-2 h-2'"
                            class="bg-theme rounded-full cursor-pointer"></div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif
