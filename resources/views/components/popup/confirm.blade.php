@props([
    'button' => [
        'colors' => [
            ['type' => 'info', 'bg' => 'bg-blue-500', 'text' => 'text-white'],
            ['type' => 'error', 'bg' => 'bg-red-500', 'text' => 'text-white'],
            ['type' => 'warning', 'bg' => 'bg-yellow-500', 'text' => 'text-white'],
            ['type' => 'success', 'bg' => 'bg-green-500', 'text' => 'text-white'],
        ],
        'text' => ['Confirm', 'Cancel'],
    ],
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
        get button () {
            const buttonConfig = @js($button);
            const color = buttonConfig.colors.find(val => (val.type === this.config.type));

            return { 
                color: color,
                text: this.config?.buttonText || buttonConfig.text,
            }
        },
        open (config) {
            this.config = { ...this.config, ...config }
            this.show = true
        },
        close () {
            this.show = false
        },
        reject () {
            this.config.onRejected && this.config.onRejected()
            this.close()
        },
        confirmed () {
            this.config.onConfirmed && this.config.onConfirmed()
            this.close()
        },
    }"
    x-on:confirm.window="open($event.detail)"
>
    <div
        x-show="show"
        x-transition.opacity
        class="fixed inset-0 flex items-center justify-center"
        style="z-index: 9000"
        >
        <div class="absolute bg-black w-full h-full opacity-80" x-on:click="close()"></div>
        <div class="relative p-4">
            <div class="max-w-lg mx-auto min-w-[250px] bg-white rounded-md border p-6 shadow-lg flex gap-3">
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

                <div class="grow self-center">
                    <div class="space-y-2 mb-4">
                        <div x-show="config.title" x-text="config.title" class="font-semibold text-lg mb-2"></div>
                        <div x-text="config.message" class="text-gray-500"></div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a 
                            x-on:click.prevent="confirmed()"
                            x-bind:class="[button.color.bg, button.color.text]" 
                            x-text="button.text[0]"
                            class="py-1.5 px-3 font-medium rounded-md" 
                        ></a>

                        <a 
                            x-on:click.prevent="reject()"
                            x-text="button.text[1]"
                            class="py-1.5 px-3 font-medium rounded-md text-gray-900 hover:bg-gray-100" 
                        ></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
