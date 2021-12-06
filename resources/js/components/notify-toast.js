export default () => ({
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
})