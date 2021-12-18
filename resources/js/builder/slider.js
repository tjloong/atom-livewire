export default (config = null) => ({
    swiper: null,
    swiperConfig: {
        enabled: false, 
        loop: true,
        navigation: {
            nextEl: '#swiper-next',
            prevEl: '#swiper-prev',
            disabledClass: 'hidden',
            hiddenClass: 'hidden',
        },
    },

    init () {
        ScriptLoader.load([
            { src: 'https://unpkg.com/swiper@7/swiper-bundle.min.js', type: 'js' },
            { src: 'https://unpkg.com/swiper@7/swiper-bundle.min.css', type: 'css' },
        ]).then(() => {
            this.getSwiperConfig()

            this.swiper = new Swiper(this.$el, this.swiperConfig)

            this.navigationToggling()
        })
    },

    navigationToggling () {
        const prev = this.$el.querySelector('#swiper-prev')
        const next = this.$el.querySelector('#swiper-next')

        if (this.swiperConfig.navigation) {
            prev.classList.remove('hidden')
            next.classList.remove('hidden')
        }
    },

    getSwiperConfig () {
        config = { ...this.swiperConfig, ...config }

        const slides = this.$el.querySelectorAll('.swiper-slide')

        if (slides.length > 1) config.enabled = true

        this.swiperConfig = config
    },
})