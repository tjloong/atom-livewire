@if ($count = count($this->checkboxes))
    <div class="flex flex-wrap items-center gap-2 p-3" wire:key="table-checkbox-actions">
        <div class="rounded-lg text-sm text-gray-500 font-medium bg-gray-100 border border-gray-300 py-0.5 px-3 flex items-center gap-2">
            <div class="shrink-0 text-xs">
                <x-icon name="check-double"/>
            </div>
            {{ __('atom::table.checkbox-actions', ['count' => $count]) }}
        </div>

        <div class="grow flex items-center justify-between gap-3 flex-wrap">
            {{ $slot }}

            <div class="flex items-center gap-2">
                @if ($attributes->get('archive'))
                    @if ($this->showArchived)
                        <x-button.restore size="sm" :count="$count" :params="$this->checkboxes"/>
                    @elseif (!$this->showTrashed)
                        <x-button.archive size="sm" :count="$count" :params="$this->checkboxes"/>
                    @endif
                @endif
            
                @if ($attributes->get('trash'))
                    @if ($this->showTrashed)
                        <x-button.restore size="sm" :count="$count" :params="$this->checkboxes"/>
                    @elseif (!$this->showArchived)
                        <x-button.trash size="sm" :count="$count" :params="$this->checkboxes"/>
                    @endif
                @endif

                @if ($attributes->get('delete'))
                    <x-button.delete size="sm" :count="$count" :params="$this->checkboxes"/>
                @endif
            </div>
        </div>
    </div>
@endif
