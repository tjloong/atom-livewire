@php
$alert = session()->pull('__alert') ?? [];
@endphp

<dialog
    x-data="alert(@js($alert))"
    x-on:alert.window="show($event.detail)"
    x-show="visible"
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 scale-90 translate-y-full"
    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
    x-transition:leave-end="opacity-0 scale-90 translate-y-full"
    class="bg-white rounded-xl shadow-lg min-w-80 max-w-screen-sm focus:outline-none">
    <form method="dialog" class="p-6 flex gap-3">
        <template x-if="alert.type" hidden>
            <div class="shrink-0">
                <div class="flex items-center justify-center">
                    <x-icon check-circle size="24" x-show="alert.type === 'success'" class="text-green-500"/>
                    <x-icon close-circle size="24" x-show="alert.type === 'error'" class="text-red-500"/>
                    <x-icon warning size="24" x-show="alert.type === 'warning'" class="text-yellow-500"/>
                    <x-icon info size="24" x-show="alert.type === 'info'" class="text-sky-500"/>
                </div>
            </div>
        </template>

        <div class="grow space-y-4">
            <div class="space-y-2">
                <div x-text="alert.title" class="grow self-center text-lg font-semibold"></div>
                <div x-text="alert.message" class="text-gray-500"></div>
            </div>

            <button
                type="button"
                x-on:click="close()"
                class="inline-flex items-center justify-center gap-1 whitespace-nowrap rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-white text-black border hover:bg-zinc-50 h-9 px-4 py-2">
                <div class="shrink-0 flex items-center justify-center -ml-1">
                    <x-icon close/>
                </div>
                @t('app.label.close')
            </button>
        </div>
    </form>
</dialog>