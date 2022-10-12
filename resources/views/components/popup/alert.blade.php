@props([
    'icons' => [
        ['type' => 'info', 'name' => 'info', 'bg' => 'bg-blue-100', 'text' => 'text-blue-400'],
        ['type' => 'error', 'name' => 'xmark', 'bg' => 'bg-red-100', 'text' => 'text-red-400'],
        ['type' => 'warning', 'name' => 'triangle-exclamation', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-400'],
        ['type' => 'success', 'name' => 'check', 'bg' => 'bg-green-100', 'text' => 'text-green-400'],
    ],
])

<div
    x-cloak
    x-data="{
        show: false,
        config: {
            title: null,
            message: null,
            type: 'info',
        },
        open (config) {
            this.config = { ...this.config, ...config }
            this.show = true
        },
        close () {
            if (this.config.onClose) this.config.onClose()
            this.show = false
        },
    }"
    x-on:alert.window="open($event.detail)"
>
    <div
        x-show="show"
        x-transition.opacity
        class="fixed inset-0 flex items-center justify-center"
        style="z-index: 9000"
    >
        <div class="absolute bg-black w-full h-full opacity-80" x-on:click="close()"></div>
        <div class="relative p-4">
            <div class="max-w-lg mx-auto min-w-[250px] bg-white rounded-md border p-4 shadow-lg flex gap-3">
                <div class="shrink-0">
                    @foreach ($icons as $icon)
                        <div
                            x-show="config.type === '{{ data_get($icon, 'type') }}'" 
                            class="w-10 h-10 rounded-full flex {{ data_get($icon, 'bg') }}"
                        >
                            <x-icon :name="data_get($icon, 'name')" class="m-auto {{ data_get($icon, 'text') }}"/>
                        </div>
                    @endforeach
                </div>

                <div class="flex-grow self-center">
                    <div class="space-y-2 mb-4">
                        <div class="font-semibold text-lg mb-2" x-show="config.title" x-text="config.title"></div>
                        <div class="text-gray-500" x-text="config.message"></div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a class="py-1.5 px-3 font-medium rounded-md text-gray-900 bg-gray-100" x-on:click.prevent="close()">
                            Close
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
