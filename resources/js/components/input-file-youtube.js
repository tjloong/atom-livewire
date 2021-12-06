export default () => ({
    urls: [],
    text: null,
    parseUrls () {
        this.urls = this.text.split(`\n`).filter(Boolean).map(url => {
            const vid = getYoutubeVid(url)

            return {
                vid,
                tn: vid ? `https://img.youtube.com/vi/${vid}/default.jpg` : null,
                valid: vid ? true : false,
            }
        })
    },
    getUrls () {
        return this.urls.filter(url => (url.valid)).map(url => (url.vid))
    },
    clear () {
        this.urls = []
        this.text = null
    },
    init () {
        this.$wire.on('finished', () => this.clear())
    },
})