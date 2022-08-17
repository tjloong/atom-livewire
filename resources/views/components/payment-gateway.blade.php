<x-box :header="$attributes->get('header')">
    <div class="grid divide-y">
        @foreach ($providers as $i => $val)
            @php $providerName = data_get($val, 'name'); @endphp
            
            <div
                x-data="{ show: @js($i === array_key_first($providers->toArray())) }"
                x-on:click.away="show = false"
            >
                <div 
                    x-on:click="show = !show" 
                    class="flex gap-3 py-3 px-4 cursor-pointer"
                >
                    <div class="grow flex items-center gap-2">
                        <div class="shrink-0 font-semibold">
                            {{ 
                                data_get($val, 'keys.title') 
                                ?? str()->headline($providerName)
                            }}
                        </div>
    
                        <div class="grow flex items-center gap-2">
                            @foreach (data_get($val, 'logos') as $logo)
                                <x-logo :name="$logo" class="h-5 max-w-[60px]"/>                        
                            @endforeach
                        </div>
                    </div>
    
                    <div class="shrink-0 pt-1">
                        <x-icon name="chevron-right" size="15px"/>
                    </div>    
                </div>
        
                <form 
                    x-show="show" 
                    x-transition
                    wire:submit.prevent="{{ $attributes->get('callback', 'submit') }}('{{ $providerName }}')"
                    class="p-4 grid gap-6"
                >
                    <div>
                        @isset($$providerName) {{ $$providerName }} @endisset
                        {{ $slot }}
                    </div>

                    <x-button.submit label="Continue" size="md"/>
                </form>
            </div>
        @endforeach
    </div>
</x-box>

