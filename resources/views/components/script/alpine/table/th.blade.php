<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tableTh', (key) => ({
            get sorted () {
                return this.$wire.get('sortBy') === key
            },

            sort () {
                if (this.$wire.get('sortBy') === key) {
                    this.$wire.set('sortOrder', this.$wire.get('sortOrder') === 'asc' ? 'desc' : 'asc')
                }
                else this.$wire.set('sortOrder', 'asc')

                this.$wire.set('sortBy', key)
            },
        }))
    })
</script>
