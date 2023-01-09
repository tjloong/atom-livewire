<div>
    @if (in_array($slug, $preventBot) && !$ref)
        <div class="min-h-screen max-w-screen-md mx-auto py-20 px-4">
            <x-alert type="error">
                {{ __('Whoops! Something happened and we are unable to continue.') }}
                <x-slot:buttons>
                    <x-button color="red" outlined
                        label="Back to Home" 
                        href="/"
                    />
                </x-slot:buttons>
            </x-alert>
        </div>
    @elseif ($this->livewire)
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