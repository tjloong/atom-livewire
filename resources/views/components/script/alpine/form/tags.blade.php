<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formTags', (value, options) => ({
            value,
            options: [],

            init () {
                this.options = options.map(opt => ({ ...opt, selected: this.value.includes(opt.value) }))
            },

            open () {
                this.$refs.dropdown.classList.remove('hidden')
                this.$refs.dropdown.classList.add('opacity-0')

                floatPositioning(this.$refs.input, this.$refs.dropdown, {
                    placement: 'bottom',
                    flip: true,
                })

                this.$refs.dropdown.classList.remove('opacity-0')
            },

            close () {
                this.$refs.dropdown.classList.add('hidden')
            },
            
            toggle (val) {
                this.options = this.options.map(opt => {
                    if (opt.value === val.value) opt.selected = !opt.selected
                    return opt
                })

                this.$dispatch('input', this.options
                    .filter(opt => (opt.selected))
                    .map(opt => (opt.value))
                )

                this.close()
            },
        }))
    })
</script>
