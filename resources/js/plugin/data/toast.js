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
                theme: 'light',
                icon: {
                    svg: {
                        success: icons['check-circle'],
                        error: icons['close-circle'],
                        warning: icons['warning'],
                        info: icons['info'],
                    }[toast.type] || null,
                    color: {
                        success: 'text-green-500',
                        error: 'text-red-500',
                        warning: 'text-yellow-500',
                        info: 'text-sky-500',
                    }[toast.type] || 'text-muted',
                },
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

        click (toast) {
            if (typeof toast.click === 'function') toast.click()
            else if (toast.href) Atom.goto(toast.href, toast.newtab)
        },
    }
}