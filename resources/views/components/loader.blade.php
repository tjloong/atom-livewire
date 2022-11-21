<div 
    x-data="{
        show: false,
        timer: null,

        init () {
            Livewire.hook('message.sent', () => {
                clearInterval(this.timer)
                this.timer = setTimeout(() => this.show = true, 500)
            })

            Livewire.hook('message.processed', () => {
                clearInterval(this.timer)
                this.show = false
            })
        },
    }"
    x-cloak
>
    <div x-show="show" x-transition.opacity class="fixed inset-0 transition-all duration-200 ease-in-out" style="z-index: 9999;">
        <div class="absolute opacity-50 w-full h-full bg-white"></div>
        <div class="absolute right-12 bottom-12 p-2 text-theme">
            <x-spinner/>
        </div>
    </div>
</div>
