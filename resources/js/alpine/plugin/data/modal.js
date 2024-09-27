export default (config) => {
    return {
        name: config.name,
        locked: config.locked,
        visible: config.visible,
        entangle: config.entangle,

        init () {
            if (this.visible) this.$nextTick(() => this.show())    
            if (this.entangle) this.show()
            this.$watch('entangle', val => val ? this.show() : this.close())
        },

        show () {
            this.$root.showModal()
            this.$dispatch('show')
            this.$nextTick(() => this.visible = true)
        },

        close () {
            this.visible = false
            this.entangle = false
            setTimeout(() => this.$root.close(), 200)
        },

        backdrop (e) {
            let rect = this.$root.getBoundingClientRect()
            let bounded = (rect.top <= event.clientY && event.clientY <= rect.top + rect.height && rect.left <= event.clientX && event.clientX <= rect.left + rect.width)

            // in backdrop
            if (!bounded) {
                e.stopPropagation()
                this.$dispatch('backdrop-click')
            }
        },
    }
}