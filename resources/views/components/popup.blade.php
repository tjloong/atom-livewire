@php
    $delay = 6000;
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
            open () {
                if (document.referrer.indexOf(location.protocol + '//' + location.host) === 0) return
                if (isPageReloaded()) return
                this.show = true
                this.roll()
            },
            roll() {
                this.interval = setInterval(() => this.next(), this.delay)
            },
            close () {
                this.show = false
            },
            next (index) {
                if (this.count <= 1) return
                clearTimeout(this.timout)
                this.fadeOut()
                this.timout = setTimeout(() => this.fadeIn(index), 500)
            },
            fadeOut () {
                this.$refs.slides.classList.add('opacity-0')
                this.$refs.slides.classList.add('duration-500')
            },
            fadeIn (index) {
                this.index = index || (this.index >= this.count -1 ? 0 : this.index + 1)
                this.$refs.slides.classList.remove('opacity-0')
            },
        }"
        x-init="setTimeout(() => open(), delay)"
        x-show="show"
        x-on:click="close()"
        x-transition.opacity.duration.500ms
        class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center overflow-auto py-20 px-5"
        style="z-index: 999;">
        <div 
            x-on:click.stop
            x-on:mouseenter="clearInterval(interval)"
            x-on:mouseleave="roll()"
            class="max-w-screen-lg min-w-[450px] bg-white rounded-lg shadow overflow-hidden relative">
            <div
                x-on:click="close()"
                class="absolute top-3 right-3 text-gray-400 cursor-pointer rounded-md border border-gray-400 flex hover:bg-black hover:text-white"
                style="width: 20px; height: 20px;">
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
