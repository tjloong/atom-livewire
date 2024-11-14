export default (config) => {
    return {
        name: config.name,
        locked: config.locked,
        visible: config.visible,
        variant: config.variant,
        entangle: config.entangle,

        init () {
            if (this.visible) this.$nextTick(() => this.show())    
            if (this.entangle) this.show()
            this.$watch('entangle', val => val ? this.show() : this.close())
        },

        show (data = null, variant = null) {
            if (variant) this.variant = variant

            this.$root.showModal()
            this.$root.dispatch('open', data, false)
            this.position()
        },

        close () {
            this.entangle = false
            this.$refs.modal.addClass('opacity-0')
            setTimeout(() => this.$refs.backdrop.addClass('opacity-0'), 100)
            setTimeout(() => this.$root.close(), 200)
        },

        position () {
            this.$refs.modal.removeClass('top-0 bottom-0 right-0 translate-x-full')
            this.$refs.modal.removeClass('top-0 bottom-0 left-0 right-0 translate-y-full')
            this.$refs.modal.removeClass('rounded-xl top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2')

            if (this.variant === 'slide') {
                this.$refs.modal.addClass('top-0 bottom-0 right-0 translate-x-full')
            }
            else if (this.variant === 'full') {
                this.$refs.modal.addClass('top-0 bottom-0 left-0 right-0 translate-y-full')
            }
            else {
                this.$refs.modal.addClass('rounded-xl top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2')
            }

            this.$refs.backdrop.removeClass('opacity-0')
            setTimeout(() => this.$refs.modal.removeClass('opacity-0 translate-x-full translate-y-full'), 150)
        },
    }
}