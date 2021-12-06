<div class="flex flex-col items-center justify-center py-8">
    <div class="p-4 rounded-full bg-gray-100 shadow mb-4 flex items-center justify-center">
        <x-icon name="{{ $attributes->get('icon') ?? 'folder-open' }}" size="32px" class="text-gray-400"/>
    </div>

    <div class="font-semibold text-lg text-gray-700">
        {{ $attributes->get('title') ?? 'No Results' }}
    </div>

    <div class="text-gray-400 font-medium text-center">
        {{ $attributes->get('subtitle') ?? 'There is nothing returned from the search' }}
    </div>
</div>
