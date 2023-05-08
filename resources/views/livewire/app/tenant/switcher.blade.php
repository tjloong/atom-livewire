@if ($this->tenants->count())
    @if ($this->tenants->count() > 1)
        <div 
            x-data="{ show: false }" 
            x-on:click.outside="show = false" 
            class="relative w-full md:w-auto"
        >
            <div 
                x-ref="anchor" 
                x-on:click="show = true" 
                class="flex items-center justify-center gap-2 cursor-pointer"
            >
                <x-icon name="fingerprint" class="text-theme"/> 
                <span class="font-medium">{{ str(tenant('name'))->limit(50) }}</span>
                <x-icon name="chevron-down" size="12"/>
            </div>

            <div 
                x-ref="dd" 
                x-show="show" 
                x-transition 
                class="absolute w-full bg-white shadow-lg border rounded-lg md:w-80"
            >
                <div class="flex flex-col divide-y">
                    @foreach ($this->tenants
                        ->filter(fn($val) => $val->pivot->is_owner)
                        ->concat($this->tenants->filter(fn($val) => !$val->pivot->is_owner)) 
                    as $tenant)
                        <div 
                            wire:click="switch(@js($tenant->id))"
                            class="py-3 px-4 flex items-center gap-2 cursor-pointer hover:bg-slate-100"
                        >
                            <x-icon name="fingerprint" class="text-gray-400 shrink-0"/>

                            <span class="grow truncate">{{ $tenant->name }}</span>

                            @if ($tenant->pivot->is_owner)
                                <span class="shrink-0 flex items-center justify-center">
                                    <x-badge label="owner" size="xs"/>
                                </span>
                            @endif

                            @if ($tenant->id === tenant('id'))
                                <span class="shrink-0 flex items-center justify-center">
                                    <x-icon name="circle-check" class="text-green-500"/>
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div>
            {{ tenant('name') }}
        </div>
    @endif
@else
    <template></template>
@endif