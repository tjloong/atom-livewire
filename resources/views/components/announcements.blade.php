@if (count($announcements))
    <div class="p-4 bg-theme">
        <div class="max-w-screen-xl mx-auto flex gap-3 justify-center text-theme-inverted">
            <x-icon name="bullhorn" class="shrink-0"/>
            <div x-data="{ 
                count: @json(count($announcements)),
                currentIndex: 0,
                run () {
                    if (this.currentIndex + 1 >= this.count) this.currentIndex = 0
                    else this.currentIndex = this.currentIndex + 1
                }
            }" x-init="setInterval(() => run(), @json($interval))">
                @foreach ($announcements as $i => $announcement)
                    <div x-show="currentIndex === {{ $i }}" class="text-lg font-medium">
                        {{ data_get($announcement, 'content') }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
