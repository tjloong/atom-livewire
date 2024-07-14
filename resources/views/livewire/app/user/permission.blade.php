<x-box>
    <div class="flex flex-col divide-y">
        @foreach ($this->permissions as $module => $actions)
            <div class="py-2 px-4 grid gap-3 md:grid-cols-12 hover:bg-slate-50">
                <div class="md:col-span-4 text-sm font-medium">
                    {{ str()->headline($module) }}
                </div>
                <div class="md:col-span-8 flex items-center gap-2 flex-wrap">
                    @foreach ($actions as $action => $permitted)
                        <div
                            wire:click="toggle({{ Js::from($module) }}, {{ Js::from($action) }})" 
                            class="flex items-center gap-2 cursor-pointer border py-0.5 px-2 rounded-md text-sm {{ 
                                $permitted ? 'bg-slate-100' : 'bg-white text-gray-400'
                            }}">
                            @if ($permitted)
                                <div class="shrink-0 text-green-500">
                                    <x-icon name="check"/>
                                </div>
                            @endif

                            <div class="grow">
                                {{ str()->headline($action) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-box>
