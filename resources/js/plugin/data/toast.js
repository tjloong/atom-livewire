export default (toasts) => {
    return {
        toasts,

        init () {
            this.$nextTick(() => {
                this.toasts = this.toasts.map(toast => (this.build(toast)))
                this.toasts.forEach(toast => this.show(toast))
            })
        },

        build (toast) {
            return {
                id: Atom.ulid(),
                visible: false,
                theme: 'dark',
                ...toast,
            }
        },

        show (toast) {
            if (toast.visible) return
            setTimeout(() => toast.visible = true, 100)
            if (!toast.permanent) setTimeout(() => this.close(toast), toast.delay || 3500)
        },

        close (toast) {
            toast.visible = false

            setTimeout(() => {
                let index = this.toasts.findIndex(item => (item.id === toast.id))
                this.toasts.splice(index, 1)
            }, 100)
        },

        push (value) {
            if (Array.isArray(value)) value.forEach(item => this.push(item))
            else {
                if (this.toasts.length >= 4) this.toasts.shift()
                this.toasts.push(this.build(value))
                this.toasts.forEach(toast => this.show(toast))
            }
        },
    }
}