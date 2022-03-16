<div
    x-cloak
    x-data="notifyConfirm"
    x-on:confirm.window="open($event.detail)"
>
    <div class="fixed inset-0 z-40 flex items-center justify-center" x-show="show" x-transition.opacity>
        <div class="absolute bg-black w-full h-full opacity-80" x-on:click="close()"></div>
        <div class="relative p-4">
            <div class="max-w-lg mx-auto bg-white rounded-md border p-4 shadow-lg flex space-x-3 min-w-[400px]">
                <div class="flex-shrink-0">
                    <div x-show="config.type === 'info'" class="w-10 h-10 rounded-full flex bg-blue-100">
                        <x-icon name="info-circle" class="text-blue-400 m-auto" size="xs"/>
                    </div>
                    <div x-show="config.type === 'error'" class="w-10 h-10 rounded-full flex bg-red-100">
                        <x-icon name="error" class="text-red-400 m-auto" size="xs"/>
                    </div>
                    <div x-show="config.type === 'warning'" class="w-10 h-10 rounded-full flex bg-yellow-100">
                        <x-icon name="error" class="text-yellow-400 m-auto" size="xs"/>
                    </div>
                    <div x-show="config.type === 'success'" class="w-10 h-10 rounded-full flex bg-green-100">
                        <x-icon name="check" class="text-green-400 m-auto" size="xs"/>
                    </div>
                </div>

                <div class="flex-grow self-center">
                    <div class="space-y-2 mb-4">
                        <div class="font-semibold text-lg mb-2" x-show="config.title" x-text="config.title"></div>
                        <div class="text-gray-500" x-text="config.message"></div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a class="py-1.5 px-3 font-medium rounded-md" x-bind:class="buttonColors[config.type]" x-on:click.prevent="confirmed()">
                            Confirm
                        </a>

                        <a class="py-1.5 px-3 font-medium rounded-md text-gray-900 hover:bg-gray-100" x-on:click.prevent="reject()">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notifyConfirm', () => ({
            show: false,
            config: {
                title: null,
                message: null,
                type: 'info',
            },
            buttonColors: {
                info: 'bg-blue-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-white',
                success: 'bg-green-500 text-white',
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
        }))
    })
</script>
