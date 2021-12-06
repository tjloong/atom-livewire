export default () => ({
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
})