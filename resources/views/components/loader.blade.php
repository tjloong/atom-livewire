<div
    x-cloak
    x-data="{
        active: false,
        timer: null,

        init () {
            Livewire.hook('message.sent', () => {
                clearInterval(this.timer)
                this.timer = setTimeout(() => this.active = true, 800)
            })

            Livewire.hook('message.processed', () => {
                clearInterval(this.timer)
                this.active = false
            })
        },
    }"
    x-bind:class="active ? 'bottom-8' : '-bottom-40'"
    class="fixed right-8 bg-black rounded-lg shadow-lg flex items-center gap-3 py-2 px-5 transition-all duration-100 ease-in-out animate-bounce"
    style="z-index: 999">
    <x-spinner size="18" class="text-theme"/>
    <div class="font-medium text-white">
        {{ tr('app.label.syncing') }}...
    </div>
</div>
