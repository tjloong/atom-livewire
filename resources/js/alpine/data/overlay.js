export default (id) => ({
    id,
    nav: null,
    show: false,

    init () {
        document.addEventListener('overlay', (e) => this.listen(e.detail))
        document.addEventListener('app-layout-nav-changed', (e) => this.nav = e.detail)
    },

    open () {
        return new Promise((resolve, reject) => {
            if (!this.show) {
                this.show = true
                
                if (this.$el.hasClass('page')) {
                    this.$el.style.top = document.querySelector('.app-layout-header').offsetHeight+'px'
                }

                this.$el.addClass('active')
                this.zIndex()
                this.scrollLock()

                setTimeout(() => {
                    this.$dispatch('open')
                    resolve()
                }, 200)
            }
            else resolve()
        })
    },

    close () {
        return new Promise((resolve, reject) => {
            if (this.show) {
                this.show = false
                this.$el.removeClass('active')
                this.zIndex(false)
                this.scrollLock()

                setTimeout(() => {
                    this.$dispatch('close')
                    resolve()
                }, 200)
            }
            else resolve()
        })
    },

    listen (data) {
        if (data.id !== this.id) return
        if (data.open) this.open()
        else this.close()
    },

    getOverlays () {
        return Array.from(document.querySelectorAll('.overlay.active'))
    },

    zIndex (n = null) {
        if (n) this.$el.style.zIndex = n
        else if (n === false) this.$el.style.zindex = null
        else {
            let overlays = this.getOverlays()
                .map(overlay => (window.getComputedStyle(overlay).getPropertyValue('z-index')))
                .map(n => +n)
                .filter(Boolean)

            if (overlays.length) {
                let max = Math.max(...overlays)
                if (max > window.getComputedStyle(this.$el).getPropertyValue('z-index')) {
                    this.$el.style.zIndex = max + 1
                }
            }
            else {
                this.$el.style.zIndex = null
            }
        }
    },

    scrollLock () {
        let overlays = this.getOverlays()
        if (overlays.length) document.body.addClass('scroll-locked')
        else if (document.body.hasClass('scroll-locked')) document.body.removeClass('scroll-locked')        
    },
})