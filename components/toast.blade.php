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
    x-data="toast(@js($toasts))"
    x-on:toast-received.window="push($event.detail)"
    data-atom-toast>
    <template x-teleport="body">
        <div class="fixed {{ $position }} space-y-3 w-full md:max-w-md" style="z-index: 999">
            <template x-for="toast in toasts" hidden>
                <div
                    x-show="toast.visible"
                    x-transition:enter="transition ease-in-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-full"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in-out duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-full"
                    x-on:click="click(toast)"
                    x-bind:class="{
                        'bg-white border text-zinc-800': toast.theme === 'light',
                        'bg-black/80 text-gray-100': toast.theme === 'dark',
                        'bg-red-500 text-gray-100': toast.theme === 'destructive',
                        'cursor-pointer': !empty(toast.href),
                    }"
                    class="rounded-lg shadow-lg w-full relative">
                    <div class="flex gap-4 py-4 px-5">
                        <div x-show="toast.icon.svg" class="shrink-0 w-8 h-8 flex items-center justify-center">
                            <atom:icon
                                x-html="toast.icon.svg"
                                x-bind:class="toast.icon.color"
                                size="24">
                            </atom:icon>
                        </div>

                        <div class="grow flex min-h-8">
                            <div class="self-center space-y-1">
                                <template x-if="toast.user?.name" hidden>
                                    <div class="flex items-center gap-2">
                                        <div
                                            x-bind:class="{
                                                'bg-gray-200 text-gray-400 border': toast.theme === 'light',
                                                'bg-gray-400 text-gray-200': toast.theme === 'dark',
                                                'bg-red-100 text-red-500': toast.theme === 'destructive',
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
                        class="appearance-none absolute top-4 right-4 flex items-center justify-center">
                        <atom:icon close/>
                    </button>
                </div>
            </template>
        </div>
    </template>
</div>