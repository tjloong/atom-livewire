<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formImage', (config) => ({
            value: null,
            shape: config.shape,
            placeholder: config.placeholder,

            init () {
                if (config.model) this.value = this.$wire.get(config.model)
                else if (config.value) this.value = config.value
            },

            select (file) {
                this.value = file.id
                this.placeholder = file.url
                
                this.$nextTick(() => this.input())
            },

            clear () {
                this.value = null
                this.placeholder = null

                this.$nextTick(() => this.input())
            },

            input () {
                this.$refs.input.dispatchEvent(new Event('input', { bubble: true }))
            },
        }))
    })
</script>
