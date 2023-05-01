<x-box header="Permissions">
    <div class="flex flex-col divide-y">
        @foreach ($this->permissions as $module => $actions)
            <div class="p-4 grid gap-4 md:grid-cols-3">
                <div class="font-semibold text-gray-600">
                    {{ str()->headline($module) }}
                </div>

                <div class="md:col-span-2">
                    @foreach ($actions as $name => $granted)
                        <div class="py-1 px-2 rounded hover:bg-slate-100">
                            <a 
                                wire:click="toggle(@js($module.'.'.$name), @js(!$granted))" 
                                class="flex items-center gap-2"
                            >
                                @if ($granted) <x-icon name="circle-check" class="text-green-500"/>
                                @else <x-icon name="circle-xmark" class="text-red-500"/>
                                @endif
                                <div class="font-medium">{{ str()->headline($name) }}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-box>