<div>
    @if ($this->livewire)
        @livewire($this->livewire, array_merge(
            ['qs' => request()->query()],
            $this->page 
                ? ['page' => $this->page]
                : []
        ))
    @elseif ($this->page)
        <div class="max-w-screen-lg mx-auto px-6 py-20">
            <div class="text-4xl font-bold mb-6">
                {{ $this->page->title }}
            </div>
            
            <div class="prose max-w-none md:prose-lg">
                {!! $this->page->content !!}
            </div>
        </div>  
    @endif
</div>