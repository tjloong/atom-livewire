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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notifyToast', () => ({
            show: false,
            timer: null,
            title: null,
            message: null,
            duration: 5000,
            type: 'info',
            icons: {
                'info': 'info-circle',
                'error': 'error',
                'warning': 'error',
                'success': 'check',
            },
            iconBgColors: {
                info: 'bg-blue-100',
                error: 'bg-red-100',
                warning: 'bg-yellow-100',
                success: 'bg-green-100',
            },
            iconTextColors: {
                info: 'text-blue-400',
                error: 'text-red-400',
                warning: 'text-yellow-400',
                success: 'text-green-400',
            },
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
