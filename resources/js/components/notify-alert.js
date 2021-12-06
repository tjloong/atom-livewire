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
    open (config) {
        this.config = { ...this.config, ...config }
        this.show = true
    },
    close () {
        this.show = false
    },
})