<div class="max-w-screen-lg mx-auto">
    <x-box header="Labels">
        <div class="grid divide-y">
            @foreach (collect($this->types)->map(fn($val) => [
                'slug' => $val,
                'label' => str()->headline($val),
                'count' => model('label')->when(
                    model('label')->enabledBelongsToAccountTrait,
                    fn($q) => $q->belongsToAccount()
                )->where('type', $val)->count(),
            ]) as $item)
                <div class="grid divide-y">
                    <div class="px-4 flex items-center gap-4">
                        <a 
                            wire:click="$set('type', @js($type === data_get($item, 'slug') ? null : data_get($item, 'slug')))"
                            class="grow py-4 flex flex-wrap items-center justify-between gap-3 text-gray-800"
                        >
                            <div class="flex items-center gap-2">
                                <x-icon name="tag" class="text-gray-400" size="16px"/>
                                <div class="font-semibold">{{ data_get($item, 'label') }}</div>
                            </div>
        
                            <div class="shrink-0 flex items-center gap-2">
                                <div class="text-gray-400 font-medium">
                                    {{ __(':count '.str()->plural('label', data_get($item, 'count')), [
                                        'count' => data_get($item, 'count'),
                                    ]) }}
                                </div>
                                <x-icon name="chevron-down" size="14px"/>
                            </div>
                        </a>

                        <x-button color="gray" size="sm"
                            label="New"
                            :href="route('app.label.create', [data_get($item, 'slug')])"
                        />
                    </div>

                    @if ($type === data_get($item, 'slug') && data_get($item, 'count') > 0)
                        <x-form.sortable
                            wire:sorted="sort"
                            :config="['handle' => '.sort-handle']"
                            class="grid divide-y"
                        >
                            @foreach ($labels as $label)
                                <div class="flex gap-2 px-2 hover:bg-slate-100" data-sortable-id="{{ $label->id }}">
                                    <div class="shrink-0 cursor-move sort-handle flex justify-center text-gray-400 p-2">
                                        <x-icon name="sort"/>
                                    </div>
                                
                                    <a href="{{ route('app.label.update', [$label->id]) }}" class="grow self-center">
                                        <div class="py-2 px-4">
                                            {{ $label->locale('name') }}

                                            @if (
                                                $str = collect($label->name)
                                                    ->filter(fn($name) => $name !== $label->locale('name'))
                                            )
                                                <div class="text-sm text-gray-500 font-medium">
                                                    {{ $str->join(' | ') }}
                                                </div>
                                            @endif
                                        </div>
                                    </a>

                                    @if ($label->children_count)
                                        <div class="text-sm font-medium text-gray-500 self-center">
                                            {{ $label->children_count }} {{ str()->plural('child', $label->children_count) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </x-form.sortable>
                    @endif
                </div>
            @endforeach
        </div>
    </x-box>
</div>