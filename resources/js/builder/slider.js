export default (config = null, thumbsConfig = null) => ({
    swiper: null,
    swiperConfig: null,
    swiperThumbs: null,
    swiperThumbsConfig: null,

    init () {
        ScriptLoader.load([
            { src: 'https://unpkg.com/swiper@7/swiper-bundle.min.js', type: 'js' },
            { src: 'https://unpkg.com/swiper@7/swiper-bundle.min.css', type: 'css' },
        ]).then(() => {
            this.getConfig()

            const thumbs = this.$el.querySelector('#swiper-thumbs')
            if (thumbs) this.swiperThumbs = new Swiper(thumbs, this.swiperThumbsConfig)

            const slider = this.$el.querySelector('#swiper-slider')
            
            this.swiper = new Swiper(slider, {
                ...this.swiperConfig,
                thumbs: this.swiperThumbs
                    ? { swiper: this.swiperThumbs }
                    : null,
            })

            this.toggleElements()
        })
    },

    toggleElements () {
        const prev = this.$el.querySelector('#swiper-prev')
        const next = this.$el.querySelector('#swiper-next')
        const pagination = this.$el.querySelector('.swiper-pagination')
        const scrollbar = this.$el.querySelector('.swiper-scrollbar')

        if (this.swiperConfig.navigation) {
            prev.classList.remove('hidden')
            next.classList.remove('hidden')
        }

        if (this.swiperConfig.pagination) pagination.classList.remove('hidden')
        if (this.swiperConfig.scrollbar) scrollbar.classList.remove('hidden')
    },

    getConfig () {
        const thumbs = this.$el.querySelector('#swiper-thumbs')
        const slides = this.$el.querySelectorAll('#swiper-slider .swiper-slide')

        if (thumbs) {
            this.swiperThumbsConfig = {
                enabled: slides.length > 1,
                spaceBetween: 16,
                slidesPerView: 5,
                ...thumbsConfig,
            }
        }

        this.swiperConfig = {
            enabled: slides.length > 1, 
            loop: true,
            navigation: {
                nextEl: '#swiper-next',
                prevEl: '#swiper-prev',
                disabledClass: 'hidden',
                hiddenClass: 'hidden',
            },
            ...config,
        }
    },
})