<div x-cloak x-data="notifyAlert" x-on:alert.window="open($event.detail)">
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
                        <a class="py-1.5 px-3 font-medium rounded-md text-gray-900 bg-gray-100" x-on:click.prevent="close()">
                            Close
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div x-cloak x-data="notifyConfirm" x-on:confirm.window="open($event.detail)">
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
                <div x-show="type === 'info'" class="w-7 h-7 rounded-full flex bg-blue-100">
                    <x-icon name="info-circle" class="text-blue-400 m-auto" size="xs"/>
                </div>
                <div x-show="type === 'error'" class="w-7 h-7 rounded-full flex bg-red-100">
                    <x-icon name="error" class="text-red-400 m-auto" size="xs"/>
                </div>
                <div x-show="type === 'warning'" class="w-7 h-7 rounded-full flex bg-yellow-100">
                    <x-icon name="error" class="text-yellow-400 m-auto" size="xs"/>
                </div>
                <div x-show="type === 'success'" class="w-7 h-7 rounded-full flex bg-green-100">
                    <x-icon name="check" class="text-green-400 m-auto" size="xs"/>
                </div>
            </div>

            <div class="flex-grow self-center">
                <div class="font-semibold" x-show="title" x-text="title"></div>
                <div class="text-gray-500 font-medium" x-text="message"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notifyAlert', () => ({
            show: false,
            config: {
                title: null,
                message: null,
                type: 'info',
            },
            open (config) {
                this.config = { ...this.config, ...config }
                this.show = true
            },
            close () {
                if (this.config.onClose) this.config.onClose()
                this.show = false
            },
        }))

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

        Alpine.data('notifyToast', () => ({
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
        }))
    })
</script>
