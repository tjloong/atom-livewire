@props([
    'icons' => [
        ['type' => 'info', 'name' => 'info-circle', 'bg' => 'bg-blue-100', 'text' => 'text-blue-400'],
        ['type' => 'error', 'name' => 'error', 'bg' => 'bg-red-100', 'text' => 'text-red-400'],
        ['type' => 'warning', 'name' => 'error', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-400'],
        ['type' => 'success', 'name' => 'check', 'bg' => 'bg-green-100', 'text' => 'text-green-400'],
    ],
])

<div
    x-cloak
    x-data="{
        show: false,
        timer: null,
        title: null,
        message: null,
        duration: 5000,
        type: 'info',
        setContent (config) {
            if (config === 'formError') this.message = 'Whoops! Something went wrong.'
            else {
                this.title = config?.title || null
                this.message = config?.message || null
                this.type = config?.type || 'info'
            }
        },
        open (config) {
            // when aldy got other toast, close them first
            if (this.show) {
                this.close()
                setTimeout(() => {
                    this.setContent(config)
                    this.show = true
                }, 50)
            }
            else {
                this.setContent(config)
                this.show = true
            }

            this.timer = setTimeout(() => this.close(), this.duration)
        },
        close () {
            clearInterval(this.timer)
            this.show = false
        },
    }"
    x-on:toast.window="open($event.detail)"
    class="fixed top-12 right-4 pt-2 pb-6 px-2 overflow-hidden space-y-2 z-40"
>
    <div
        x-show="show"
        x-transition
        class="max-w-sm mx-auto min-w-[300px] bg-white rounded-md shadow-lg border p-4"
    >
        <div class="flex gap-3">
            <div class="shrink-0">
                @foreach ($icons as $icon)
                    <div
                        x-show="type === '{{ data_get($icon, 'type') }}'" 
                        class="w-8 h-8 rounded-full flex {{ data_get($icon, 'bg') }}"
                    >
                        <x-icon :name="data_get($icon, 'name')" size="xs" class="m-auto {{ data_get($icon, 'text') }}"/>
                    </div>
                @endforeach
            </div>

            <div class="grow self-center">
                <div class="font-semibold" x-show="title" x-text="title"></div>
                <div class="text-gray-500 font-medium" x-text="message"></div>
            </div>

            <a class="text-gray-500 py-1" x-on:click.prevent="close()">
                <x-icon name="x"/>
            </a>
        </div>
    </div>
</div>
