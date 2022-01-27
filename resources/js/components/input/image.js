export default () => ({
    select (file) {
        this.value = file.id
        this.placeholder = file.url
        this.$nextTick(() => this.input())
    },

    clear () {
        this.value = this.placeholder = null
        this.$nextTick(() => this.input())
    },

    input () {
        this.$refs.input.dispatchEvent(new Event('change'))
    },
})