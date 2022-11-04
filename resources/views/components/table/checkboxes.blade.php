<div 
    id="table-checkboxes"
    class="flex items-center divide-x divide-gray-300 rounded-full text-sm bg-gray-200"
>
    <div class="flex items-center gap-2 py-1 px-3">
        <x-icon name="check" class="text-green-500"/>
        <div class="flex items-center gap-1 font-medium">
            {{ __(':count Selected', ['count' => $attributes->get('count')]) }}
        </div>
    </div>

    <div
        wire:click="toggleCheckbox('*')"
        class="flex items-center gap-2 justify-center py-1 px-3 cursor-pointer"
    >
        <x-icon name="check-double" class="text-gray-400" size="12"/>
    </div>
</div>
