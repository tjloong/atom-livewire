<div
    x-cloak
    x-data="notifyAlert"
    x-on:alert.window="open($event.detail)"
>
    <div class="fixed inset-0 z-40 flex items-center justify-center" x-show="show" x-transition.opacity>
        <div class="absolute bg-black w-full h-full opacity-80" x-on:click="close()"></div>
        <div class="relative p-4">
            <div class="max-w-lg mx-auto bg-white rounded-md border p-4 shadow-lg flex space-x-3 min-w-[400px]">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center" x-bind:class="iconBgColors[config.type]">
                        <x-icon x-bind:name="icons[config.type]" x-bind:class="iconTextColors[config.type]"/>
                    </div>
                </div>

                <div class="flex-grow self-center">
                    <div class="space-y-2 mb-4">
                        <div class="font-semibold mb-2" x-show="config.title" x-text="config.title"></div>
                        <div class="text-sm text-gray-500" x-text="config.message"></div>
                    </div>
                    <div class="flex items-center space-x-2 text-sm">
                        <a class="py-1.5 px-3 font-medium rounded-md text-gray-900 bg-gray-100" x-on:click.prevent="close()">
                            Close
                        </a>
                    </div>
                </div>
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
            open (config) {
                this.config = { ...this.config, ...config }
                this.show = true
            },
            close () {
                this.show = false
            },
        }))
    })
</script>
