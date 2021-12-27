export default (value = null) => ({
    value: {
        title: null,
        description: null,
        image: null,
        ...value,
    },

    init () {
        this.$watch('value.title', val => this.$dispatch('seo-updated', this.value))
        this.$watch('value.description', val => this.$dispatch('seo-updated', this.value))
        this.$watch('value.image', val => this.$dispatch('seo-updated', this.value))
    }
})