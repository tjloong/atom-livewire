<form wire:submit.prevent="save">
    <div x-data="{ title: $wire.get('blog.title') }" class="relative px-2 mb-6">
        <span x-show="!title" class="absolute text-3xl font-bold text-gray-400 pointer-events-none">
            Blog Title
        </span>
        <input
            x-model="title"
            wire:model.lazy="blog.title"
            type="text"
            class="w-full bg-transparent appearance-none border-0 p-0 text-3xl font-bold focus:ring-0"
            autofocus
        >
        @error('blog.title') <div class="text-sm font-medium text-red-500 mt-2">{{ $messages }}</div> @enderror
    </div>

    <div class="mb-6">
        <x-input.richtext wire:model.debounce.500ms="blog.content">
            {!! $blog->content !!}
        </x-input.richtext>
        <div class="text-xs text-gray-500 mt-2">
            @if ($autosavedAt)
                Auto saved at {{ format_date($autosavedAt, 'time-full') }}
            @endif
        </div>
    </div>
    
    <x-button type="submit" color="green" icon="check">
        Save Blog
    </x-button>
</form>
