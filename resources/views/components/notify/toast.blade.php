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
        timer: null,
        toasts: [],
        config: {
            title: null,
            message: null,
            type: 'info',
            duration: 4000,
            colors: @js($colors),
        },
        open (val) {
            const uid = random()
            const config = { ...this.config, ...val, uid }

            if (!config.title && config.message) {
                config.title = config.message
                config.message = null
            }

            this.toasts.unshift(config)
            setTimeout(() => this.close(uid), config.duration)
        },
        close (uid) {
            this.toasts = this.toasts.filter(toast => toast.uid !== uid)
        },
    }"
    x-on:toast.window="open($event.detail)"
    x-bind:class="toasts.length && 'pt-2 px-2 pb-6 w-[300px]'"
    class="fixed top-12 right-4 overflow-hidden flex flex-col gap-2"
    style="z-index: 9000"
>
    <template x-for="toast in toasts" x-bind:key="toast.uid">
        <div class="bg-white rounded-lg shadow-lg border p-4">
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div 
                        x-bind:class="toast.colors[toast.type].icon" 
                        class="shrink-0 rounded-full flex items-center justify-center"
                        style="width: 22px; height: 22px"
                    >
                        <x-icon x-show="toast.type === 'info'" name="info" size="12"/>
                        <x-icon x-show="toast.type === 'error'" name="xmark" size="12"/>
                        <x-icon x-show="toast.type === 'success'" name="check" size="12"/>
                        <x-icon x-show="toast.type === 'warning'" name="triangle-exclamation" size="12"/>
                    </div>

                    <div
                        x-text="toast.title"
                        x-bind:class="toast.message ? 'font-semibold' : 'font-medium'"
                        class="grow"
                    ></div>

                    <div class="shrink-0">
                        <x-close x-on:click="close(toast.uid)"/>
                    </div>
                </div>

                <template x-if="toast.message">
                    <div x-text="toast.message" class="text-sm text-gray-500"></div>
                </template>
            </div>
        </div>
    </template>
</div>
