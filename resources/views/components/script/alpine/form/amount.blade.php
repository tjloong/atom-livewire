<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formAmount', (value) => ({
            value,

            stringToNumber (val) {
                if (Number.isFinite(val)) return val

                val = val.replace(/[^\d\.]+/g, '')
                val = val.replace(/(\..*)\./g, '$1')
                val = parseFloat(val)
                val = !val || !Number.isFinite(val) ? null : val

                return val || 0
            },

            updateValue (val) {
                this.value = this.stringToNumber(val)
                this.$nextTick(() => this.$refs.input.dispatchEvent(new Event('input', { bubbles: true })))
            },
        }))
    })
</script>
