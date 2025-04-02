export default (config) => {
    return {
        value: config.value,
        max: config.max,
        accept: config.accept,
        multiple: config.multiple,
        visibility: config.visibility,
        trigger: null,

        init () {
            this.trigger = this.$refs.trigger.querySelector('[data-atom-uploader-trigger]')

            if (!this.trigger) {
                let children = Array.from(this.$refs.trigger.children)
                if (children.length === 1 && children[0].tagName === 'BUTTON') this.trigger = children[0]
            }

            this.trigger?.addEventListener('click', (e) => {
                if (!this.trigger.hasClass('is-loading')) {
                    this.$refs.input.click()
                }
            })
        },

        read (files) {
            this.loading()

            Atom
                .upload(files, {
                    max: this.max,
                    accept: this.accept,
                    multiple: this.multiple,
                    visibility: this.visibility,
                    progress: (value) => this.progress(value),
                })
                .then(res => {
                    this.value = res.id
                    this.$dispatch('uploaded', res.files)
                    Livewire?.emit('uploaded', res.files)
                })
                .catch(({ message }) => Atom.alert({ title: 'Unable to Upload', message }, 'error'))
                .finally(() => this.loading(false))
        },

        loading (bool = true) {
            if (bool) {
                this.$refs.trigger.addClass('is-loading')
                this.trigger.addClass('is-loading')
            }
            else {
                this.$refs.trigger.removeClass('is-loading')
                this.trigger.removeClass('is-loading')
            }
        },

        progress (value) {
            Atom.toast(`${t('uploaded')} ${value}`, 'info')
        },
    }
}