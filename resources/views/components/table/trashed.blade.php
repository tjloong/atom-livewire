@if ($count = $attributes->get('count') ?? $this->query->onlyTrashed()->count())
    <div
        x-data="{
            get clearable () {
                return this.$wire.get('filters.status') === 'trashed'
            }
        }"
        class="bg-gray-100 rounded-full font-medium text-sm py-1 px-3 flex items-center gap-3"
    >
        {{ __(':count Trashed', ['count' => $count]) }}

        <div 
            x-show="clearable" 
            x-on:click="$dispatch('confirm', {
                title: '{{ __('Empty Trashed') }}',
                message: '{{ __('This will empty all trashed records. Are you sure?') }}',
                type: 'error',
                onConfirmed: () => $wire.emptyTrashed(),
            })"
            class="text-blue-600 cursor-pointer"
        >
            {{ __('Clear') }}
        </div>

        <div 
            x-show="!clearable" 
            wire:click="$set('filters.status', 'trashed')"
            class="text-blue-600 cursor-pointer"
        >
            {{ __('Show') }}
        </div>

        <div 
            x-show="clearable" 
            wire:click="$set('filters.status', null)"
            class="text-blue-600 cursor-pointer"
        >
            {{ __('Cancel') }}
        </div>
    </div>
@endif