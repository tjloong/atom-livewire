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
                    <x-icon check-circle size="24" x-show="config.type === 'success'" class="text-green-500"/>
                    <x-icon close-circle size="24" x-show="config.type === 'error'" class="text-red-500"/>
                    <x-icon warning size="24" x-show="config.type === 'warning'" class="text-yellow-500"/>
                    <x-icon info size="24" x-show="config.type === 'info'" class="text-sky-500"/>
                </div>
            </div>
        </template>

        <div class="grow space-y-4">
            <div class="space-y-2">
                <div x-text="config.title" class="grow self-center text-lg font-semibold"></div>
                <div x-text="config.message" class="text-gray-500"></div>
            </div>

            <div class="flex items-center gap-2 justify-end">
                <button
                    type="button"
                    x-show="!accepting"
                    x-text="config.buttons?.cancel || t('app.label.cancel')"
                    x-on:click="cancel()"
                    class="inline-flex items-center justify-center gap-1 whitespace-nowrap rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-white text-black border hover:bg-zinc-50 h-9 px-4 py-2">
                </button>

                <button
                    type="button"
                    x-show="!canceling"
                    x-text="config.buttons?.confirm || t('app.label.confirm')"
                    x-on:click="accept()"
                    x-bind:class="{
                        'bg-sky-500 text-sky-100 border-sky-500 hover:bg-sky-600': config.type === 'info',
                        'bg-red-500 text-red-100 border-red-500 hover:bg-red-600': config.type === 'error',
                        'bg-green-500 text-green-100 border-green-500 hover:bg-green-600': config.type === 'success',
                        'bg-yellow-500 text-yellow-100 border-yellow-500 hover:bg-yellow-600': config.type === 'warning',
                        'bg-theme text-theme-light border-theme hover:bg-theme-dark': !config.type,
                    }"
                    class="inline-flex items-center justify-center gap-1 whitespace-nowrap rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border h-9 px-4 py-2">
                </button>
            </div>
        </div>
    </form>
</dialog>