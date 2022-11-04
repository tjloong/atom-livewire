<div x-data="{ show: false }">
    <div
        x-tooltip="Filters"
        x-on:click.prevent="show = true"
        class="cursor-pointer p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200"
    >
        <x-icon name="sliders" class="m-auto"/>
    </div>

    <div x-show="show" x-transition.opacity class="fixed inset-0 z-40">
        <div x-on:click="show = false" class="fixed inset-0 bg-black/50"></div>
        <div class="absolute top-0 right-0 bottom-0 bg-white shadow-lg w-10/12 md:max-w-sm">
            <div class="flex flex-col divide-y h-full">
                <div class="flex items-center gap-2 justify-between p-4">
                    <div class="text-lg font-semibold">
                        {{ __('Filters') }}
                    </div>
                    <x-close x-on:click="show = false"/>
                </div>

                <div class="grow overflow-auto">
                    <div class="p-6 flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
