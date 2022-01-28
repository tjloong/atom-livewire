export default () => ({
    init () {
        if (!this.$el.classList.contains('bg-theme')) return

        const rgb = window.getComputedStyle(this.$el, null).getPropertyValue('background-color')
        const hex = `#${rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/).slice(1).map(n => parseInt(n, 10).toString(16).padStart(2, '0')).join('')}`
        const luma = this.getLuma(hex)

        // light bg
        if (luma > 128) {
            this.$el.classList.remove('text-white')
            this.$el.classList.add('text-gray-800')
        }
    },

    getLuma (color) {
        color = color.substring(1) // strip #
        const rgb = parseInt(color, 16) // convert rrggbb to decimal
        const r = (rgb >> 16) & 0xff  // extract red
        const g = (rgb >>  8) & 0xff  // extract green
        const b = (rgb >>  0) & 0xff  // extract blue

        return 0.2126 * r + 0.7152 * g + 0.0722 * b // per ITU-R BT.709
    }
})