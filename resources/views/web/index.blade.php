<div>
    @if ($page)
        <div class="max-w-screen-lg mx-auto px-6 py-20">
            <div class="text-3xl font-bold mb-6">
                {{ $page->title }}
            </div>
            
            <div class="prose max-w-none">
                {!! $page->content !!}
            </div>
        </div>    
    @else
        @livewire($livewire, key('web.index'))
    @endif
</div>