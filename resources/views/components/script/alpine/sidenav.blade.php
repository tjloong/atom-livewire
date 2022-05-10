<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sidenav', (config) => ({
            show: false,
            value: null,

            init () {
                this.setValue()
                Livewire.hook('message.received', (message, component) => this.setValue())
            },

            setValue () {
                if (config.model) this.value = this.$wire.get(config.model)
                else if (config.value) this.value = config.value
            },

            select (val) {
                this.show = !this.show
                this.value = val
                this.$nextTick(() => this.$dispatch('input', this.value))
            }
        }))
    })
</script>
