@if (!enabled_module('permissions') || tier('root'))
    <template></template>
@else
    <x-box header="Permissions">
        <div class="flex flex-col divide-y max-h-[450px] overflow-auto">
            @foreach ($this->permissions as $module => $actions)
                <div class="p-4 grid gap-4 md:grid-cols-3">
                    <div class="flex items-center gap-2 px-2 font-semibold">
                        <x-icon name="lock" class="text-gray-400"/>
                        {{ str()->headline($module) }}
                    </div>

                    <div class="md:col-span-2 flex items-center flex-wrap gap-3">
                        @foreach ($actions as $name => $granted)
                            <a 
                                wire:click="toggle(@js($module.'.'.$name), @js(!$granted))" 
                                class="px-2 flex items-center gap-2 hover:font-bold"
                            >
                                @if ($granted) <x-icon name="circle-check" class="text-green-500"/>
                                @else <x-icon name="circle-xmark" class="text-red-500"/>
                                @endif
                                {{ str()->headline($name) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </x-box>
@endif