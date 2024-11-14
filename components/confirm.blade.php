<dialog
    x-data="confirm()"
    x-on:confirm.window="show($event.detail)"
    x-show="visible"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 scale-90 translate-y-full"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-90 translate-y-full"
    class="bg-white rounded-xl shadow-lg min-w-80 max-w-screen-sm focus:outline-none">
    <form method="dialog" class="p-6 flex gap-3">
        <template x-if="config.type" hidden>
            <div class="shrink-0">
                <div class="flex items-center justify-center">
                    <atom:icon size="24"
                        x-html="icons[{
                            success: 'check-circle',
                            warning: 'warning',
                            info: 'info',
                            error: 'close-circle',
                        }[config.type]]"
                        x-bind:class="{
                            success: 'text-green-500',
                            warning: 'text-yellow-500',
                            info: 'text-sky-500',
                            error: 'text-red-500',
                        }[config.type] || 'text-muted'">
                    </atom:icon>
                </div>
            </div>
        </template>

        <div class="grow space-y-4">
            <div class="space-y-2">
                <div x-text="config.title" class="grow self-center text-lg font-semibold"></div>
                <div x-text="config.message" class="text-gray-500"></div>
            </div>

            <template x-if="config.phrase" hidden>
                <div class="space-y-2">
                    <div x-text="t('please-enter-phrase-to-continue', { phrase: config.phrase })" class="text-sm font-medium"></div>
                    <input type="text" 
                        x-ref="phrase"
                        x-model="phrase"
                        class="w-full px-3 h-10 bg-white border border-zinc-200 shadow-sm rounded-lg focus:outline-none">
                </div>
            </template>

            <div x-show="validatePhrase()" class="flex items-center gap-2 justify-end">
                <button
                    type="button"
                    x-show="!accepting"
                    x-text="config.buttons?.cancel || t('cancel')"
                    x-on:click="cancel()"
                    class="inline-flex items-center justify-center gap-1 whitespace-nowrap rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-white text-black border hover:bg-zinc-50 h-9 px-4 py-2">
                </button>

                <button
                    type="button"
                    x-show="!canceling"
                    x-text="config.buttons?.confirm || t('confirm')"
                    x-on:click="accept()"
                    x-bind:class="{
                        info: 'bg-sky-500 text-sky-100 border-sky-500 hover:bg-sky-600',
                        error: 'bg-red-500 text-red-100 border-red-500 hover:bg-red-600',
                        success: 'bg-green-500 text-green-100 border-green-500 hover:bg-green-600',
                        warning: 'bg-yellow-500 text-yellow-100 border-yellow-500 hover:bg-yellow-600',
                    }[config.type] || 'bg-theme text-theme-light border-theme hover:bg-theme-dark'"
                    class="inline-flex items-center justify-center gap-1 whitespace-nowrap rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border h-9 px-4 py-2">
                </button>
            </div>
        </div>
    </form>
</dialog>