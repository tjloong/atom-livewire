export default (config) => {
    return {
        show: false,
        gallery: [],
        pointer: null,

        open ({ gallery = [], slide }) {
            // setup gallery
            this.gallery = gallery.map(item => {
                if (typeof item === 'string') return { endpoint: item, is_image: true }
                else return item
            })

            // setup pointer
            if (typeof slide === 'number') this.pointer = slide
            else if (typeof slide === 'string') this.pointer = this.gallery.findIndex(item => (item.endpoint === slide))
            else if (slide.id) this.pointer = this.gallery.findIndex(item => (item.id === slide.id))
            else if (slide.endpoint) this.pointer = this.gallery.findIndex(item => (item.endpoint === slide.endpoint))
            else if (slide.url) this.pointer = this.gallery.findIndex(item => (item.url === slide.url))
            if (!this.pointer) this.pointer = 0

            this.show = true
        },

        close () {
            this.show = false
        },

        prev () {
            let prev = ((this.pointer + this.gallery.length) - 1) % this.gallery.length
            this.pointer = null
            setTimeout(() => this.pointer = prev, 150)
        },

        next () {
            let next = (this.pointer + 1) % this.gallery.length
            this.pointer = null
            setTimeout(() => this.pointer = next, 150)
        },
    }
}
