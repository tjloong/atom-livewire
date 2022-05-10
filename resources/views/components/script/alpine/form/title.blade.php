<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formTitle', (config) => ({
            value: null,

            init () {
                if (config.model) this.value = this.$wire.get(config.model)
                else if (config.value) this.value = config.value
            },
        }))
    })
</script>
