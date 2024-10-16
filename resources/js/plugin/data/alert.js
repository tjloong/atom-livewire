export default (alert) => {
    return {
        alert,
        visible: false,

        init () {
            if (!empty(this.alert)) this.show()
        },

        show (alert = null) {
            if (alert) this.alert = alert
            this.$root.showModal()
            this.$nextTick(() => this.visible = true)
        },

        close () {
            this.visible = false
            setTimeout(() => this.$root.close(), 200)
        },        
    }
}