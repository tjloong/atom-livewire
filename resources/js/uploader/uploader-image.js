export default () => ({
    urls: [],
    text: null,
    parseUrls () {
        this.urls = this.text.split(`\n`).filter(Boolean).map(url => ({
            href: url,
            valid: true,
        }))
    },
    getUrls () {
        return this.urls.filter(url => (url.valid)).map(url => (url.href))
    },
    clear () {
        this.urls = []
        this.text = null
    },
    init () {
        this.$wire.on('finished', () => this.clear())
    },
})