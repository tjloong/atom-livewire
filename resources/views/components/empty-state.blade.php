<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-8 gap-4']) }}>
    <div class="p-6 rounded-full bg-gray-100 shadow flex m-auto">
        <x-icon name="{{ $attributes->get('icon') ?? 'folder-open' }}" size="lg" class="text-gray-400"/>
    </div>

    <div class="grid gap-4">
        <div class="text-center">
            <div class="font-semibold text-lg text-gray-700">
                {{ __($attributes->get('title') ?? 'No Results') }}
            </div>
        
            <div class="text-gray-400 font-medium text-center">
                {{ __($attributes->get('subtitle') ?? 'There is nothing returned from the search') }}
            </div>
        </div>

        @if ($slot->isNotEmpty())
            <div class="text-center">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
