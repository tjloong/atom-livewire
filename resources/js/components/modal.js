export default () => ({
    show: false,
    
    open () {
        document.documentElement.classList.add('overflow-hidden')
        this.show = true
    },
    close () {
        document.documentElement.classList.remove('overflow-hidden')
        this.show = false
    },
})