<div
    x-cloak
    x-data="notifyToast"
    x-on:toast.window="open($event.detail)"
    class="fixed top-12 right-4 pt-2 pb-6 px-2 overflow-hidden space-y-2 z-40"
>
    <div
        class="max-w-sm min-w-[300px] mx-auto bg-white rounded-md shadow-lg border p-4"
        x-show="show"
        x-transition
    >
        <a class="float-right text-gray-500" x-on:click.prevent="close()">
            <x-icon name="x"/>
        </a>
        <div class="flex space-x-2">
            <div class="flex-shrink-0">
                <div class="w-7 h-7 rounded-full flex items-center justify-center" x-bind:class="iconBgColors[type]">
                    <x-icon x-bind:name="icons[type]" size="18px" x-bind:class="iconTextColors[type]"/>
                </div>
            </div>

            <div class="flex-grow self-center">
                <div class="font-semibold text-sm" x-show="title" x-text="title"></div>
                <div class="text-gray-500 font-medium text-sm" x-text="message"></div>
            </div>
        </div>
    </div>
</div>