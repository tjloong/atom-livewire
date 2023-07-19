@tier('root')
    <template></template>
@else
    <x-box header="Data Visibility">
        <div class="flex flex-col divide-y">
            @foreach ($this->visibilities as $val)
                <div wire:click="toggle(@js(data_get($val, 'value')))" class="py-2 px-4 flex items-center gap-3 cursor-pointer hover:bg-slate-100">
                    <div class="shrink-0 flex items-center justify-center">
                        @if ($this->visibility === data_get($val, 'value')) <x-icon name="circle-check" class="text-green-500"/>
                        @else <x-icon name="circle-minus" class="text-gray-400"/>
                        @endif
                    </div>

                    <div class="grow">
                        {{ data_get($val, 'label') }}<br>
                        <div class="text-sm text-gray-500">{{ data_get($val, 'description') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-box>
@endtier