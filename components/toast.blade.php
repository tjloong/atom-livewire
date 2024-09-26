@php
$toasts = session()->pull('__toasts') ?? [];

$position = [
    'top-end' => 'top-0 left-0 right-0 p-5 md:top-10 md:right-5 md:left-auto md:p-0',
    'top-start' => 'top-0 left-0 right-0 p-5 md:top-10 md:right-auto md:left-5 md:p-0',
    'top-center' => 'top-0 left-0 right-0 p-5 md:top-10 md:left-1/2 md:right-auto md:-translate-x-1/2',
    'bottom-end' => 'bottom-0 left-0 right-0 p-5 md:bottom-10 md:right-5 md:left-auto md:p-0',
    'bottom-start' => 'bottom-0 left-0 right-0 p-5 md:bottom-10 md:right-auto md:left-5 md:p-0',
    'bottom-center' => 'bottom-0 left-0 right-0 p-5 md:bottom-10 md:left-1/2 md:right-auto md:-translate-x-1/2',
][$attributes->get('position', 'bottom-end')];
@endphp

<div
    x-data="{
        toasts: @js($toasts),

        init () {
            this.$nextTick(() => {
                this.toasts = this.toasts.map(toast => (this.build(toast)))
                this.toasts.forEach(toast => this.show(toast))
            })
        },

        build (toast) {
            toast = typeof toast.message === 'string' ? toast : { ...toast.message, type: toast.type }

            return {
                id: atom.ulid(),
                visible: false,
                variant: 'dark',
                ...toast,
                title: toast.title ? tr(toast.title) : null,
                message: toast.message ? tr(toast.message) : null,
            }
        },

        show (toast) {
            if (toast.visible) return
            setTimeout(() => toast.visible = true, 100)
            if (!toast.permanent) setTimeout(() => this.close(toast), toast.delay || 3500)
        },

        close (toast) {
            toast.visible = false

            setTimeout(() => {
                let index = this.toasts.findIndex(item => (item.id === toast.id))
                this.toasts.splice(index, 1)
            }, 100)
        },

        push (value) {
            if (Array.isArray(value)) value.forEach(item => this.push(item))
            else {
                if (this.toasts.length >= 4) this.toasts.shift()
                this.toasts.push(this.build(value))
                this.toasts.forEach(toast => this.show(toast))
            }
        },
    }"
    x-on:toast-received.window="push($event.detail)"
    data-atom-toast>
    <template x-teleport="body">
        <div class="fixed {{ $position }} space-y-3 w-full md:max-w-md" style="z-index: 999">
            <template x-for="toast in toasts" hidden>
                <div
                    x-show="toast.visible"
                    x-transition.duration.200
                    x-bind:class="{
                        'bg-white border text-zinc-800': toast.variant === 'light',
                        'bg-black/80 text-gray-100': toast.variant === 'dark',
                        'bg-red-500 text-gray-100': toast.variant === 'destructive',
                    }"
                    class="rounded-lg shadow w-full relative transition ease-in-out">
                    <div x-bind:class="{
                        'flex gap-3 py-3 pl-3 pr-5': toast.type,
                        'flex gap-3 py-3 px-5': !toast.type,
                    }">
                        <template x-if="toast.type" hidden>
                            <div class="shrink-0 w-8 h-8 flex items-center justify-center">
                                <x-icon check-circle size="24" x-show="toast.type === 'success'" class="text-green-500"/>
                                <x-icon close-circle size="24" x-show="toast.type === 'error'" class="text-red-500"/>
                                <x-icon warning size="24" x-show="toast.type === 'warning'" class="text-yellow-500"/>
                                <x-icon info size="24" x-show="toast.type === 'info'" class="text-sky-500"/>
                            </div>
                        </template>

                        <div class="grow flex min-h-8">
                            <div class="self-center space-y-1">
                                <template x-if="toast.user?.name" hidden>
                                    <div class="flex items-center gap-2">
                                        <div
                                            x-bind:class="{
                                                'bg-gray-200 text-gray-400 border': toast.variant === 'light',
                                                'bg-gray-400 text-gray-200': toast.variant === 'dark',
                                                'bg-red-100 text-red-500': toast.variant === 'destructive',
                                            }"
                                            class="w-5 h-5 rounded-full relative overflow-hidden">
                                            <template x-if="toast.user.avatar">
                                                <div class="absolute inset-0 z-1">
                                                    <img x-bind:src="toast.user.avatar" class="w-full h-full object-cover">
                                                </div>
                                            </template>

                                            <template x-if="!toast.user.avatar">
                                                <div x-text="toast.user.name.charAt(0)" class="absolute inset-0 z-1 text-xs uppercase leading-none flex items-center justify-center"></div>
                                            </template>
                                        </div>

                                        <div x-text="toast.user.name" class="font-medium text-sm"></div>
                                    </div>
                                </template>

                                <template x-if="toast.title" hidden>
                                    <div x-text="toast.title" class="font-medium"></div>
                                </template>

                                <div
                                    x-text="toast.message"
                                    x-bind:class="toast.title && 'text-sm'">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        x-on:click="close(toast)"
                        class="appearance-none absolute top-0.5 right-0.5 p-2 flex items-center justify-center">
                        <x-icon close/>
                    </button>
                </div>
            </template>
        </div>
    </template>
</div>