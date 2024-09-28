export default () => {
    return {
        config: {},
        visible: false,
        accepting: false,
        canceling: false,

        show (config = null) {
            if (config?.type === 'delete') {
                this.config = {
                    ...config,
                    title: t(config.title || 'app.alert.delete.title'),
                    message: t(config.message || 'app.alert.delete.message'),
                    type: 'error',
                }
            }
            else if (config?.type === 'trash') {
                this.config = {
                    ...config,
                    title: t(config.title || 'app.alert.trash.title'),
                    message: t(config.message || 'app.alert.trash.message', config.count),
                    type: 'error',
                }
            }
            else if (config) {
                this.config = {
                    ...config,
                    title: t(config.title || 'app.label.please-confirm'),
                    message: t(config.message),
                }
            }

            this.$root.showModal()
            this.$nextTick(() => this.visible = true)
        },

        close () {
            this.visible = false
            setTimeout(() => this.$root.close(), 200)
        },

        accept () {
            this.accepting = true

            this.promise(this.config.onAccept)
                .then(() => this.accepting = false)
                .then(() => this.close())
        },

        cancel () {
            this.canceling = true

            return this.promise(this.config.onCancel)
                .then(() => this.canceling = false)
                .then(() => this.close())
        },

        promise (callback) {
            return new Promise((resolve, reject) => {
                if (callback) {
                    if (this.config.livewireId) {
                        let wire = Livewire.find(this.config.livewireId)
                        if (wire) wire.call(callback)
                        else console.error('Unable to find livewire component ID '+this.config.livewireId)
                    }
                    else callback()
                }

                resolve()
            })
        },
    }
}