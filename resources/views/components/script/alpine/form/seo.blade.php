<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('seoInput', (config) => ({
            value: {
                title: null,
                description: null,
                image: null,
            },

            init () {
                if (config.model) this.value = { ...this.value, ...this.$wire.get(config.model) }
                else if (config.value) this.value = { ...this.value, ...config.value }
            },

            input () {
                this.$nextTick(() => this.$dispatch('seo-updated', this.value))
            }
        }))
    })
</script>
