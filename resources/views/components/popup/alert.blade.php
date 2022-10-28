@props([
    'colors' => [
        'info' => ['icon' => 'bg-blue-100 text-blue-500'],
        'error' => ['icon' => 'bg-red-100 text-red-500'],
        'warning' => ['icon' => 'bg-yellow-100 text-yellow-500'],
        'success' => ['icon' => 'bg-green-100 text-green-500'],
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
            colors: @js($colors),
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
        <div class="absolute inset-0 bg-black/80" x-on:click="close()"></div>
        <div class="relative p-4">
            <div class="max-w-lg mx-auto min-w-[250px] bg-white rounded-xl border shadow overflow-hidden">
                <div class="flex flex-col gap-4 p-6">
                    <div class="flex items-center gap-3">
                        <div 
                            x-bind:class="config.colors[config.type].icon" 
                            class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                        >
                            <x-icon name="info" x-show="config.type === 'info'"/>
                            <x-icon name="xmark" x-show="config.type === 'error'"/>
                            <x-icon name="check" x-show="config.type === 'success'"/>
                            <x-icon name="triangle-exclamation" x-show="config.type === 'warning'"/>
                        </div>

                        <div 
                            x-text="config.title" 
                            class="grow font-semibold text-lg"
                        ></div>
                    </div>

                    <div x-text="config.message" class="text-gray-700 font-medium"></div>
                </div>

                <div class="bg-gray-100 py-4 px-6 flex items-center gap-2">
                    <a 
                        x-on:click.prevent="close()"
                        class="text-gray-900" 
                    >
                        {{ __('Close') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
