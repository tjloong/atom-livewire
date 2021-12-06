export default () => ({
    show: false,
    timer: null,

    init () {
        Livewire.hook('message.sent', () => {
            this.timer = setTimeout(() => this.show = true, 500)
        })

        Livewire.hook('message.processed', () => {
            clearInterval(this.timer)
            this.show = false
        })
    },
})
