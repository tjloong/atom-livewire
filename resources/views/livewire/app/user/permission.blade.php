<atom:card inset>
    <div class="divide-y">
        @foreach ($this->permissions as $module => $actions)
            <div class="p-4 grid gap-3 items-center md:grid-cols-12">
                <div class="md:col-span-4 text-sm font-medium">
                    {{ str()->headline($module) }}
                </div>
                <div class="md:col-span-8 flex items-center gap-2 flex-wrap">
                    @foreach ($actions as $action => $permitted)
                        <div
                            wire:click="toggle({{ js($module) }}, {{ js($action) }})" 
                            class="flex items-center gap-2 cursor-pointer border py-0.5 px-2 rounded-md text-sm {{ 
                                $permitted ? 'bg-zinc-100' : 'bg-white text-muted-more'
                            }}">
                            @if ($permitted)
                                <atom:icon check class="shrink-0 text-green-500" size="13"/>
                            @else
                                <atom:icon stop size="13"/>
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
</atom:card>
