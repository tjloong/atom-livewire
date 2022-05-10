<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formPicker', (getter, labelKey) => ({
            labelKey,
            show: false,
            text: null,
            loading: true,
            options: [],
            paginator: null,

            fetch (page = 1) {
                this.loading = true

                this.$wire[getter](page, this.text).then(res => {
                    this.paginator = res
                    this.options = page === 1 ? res.data : this.options.concat(res.data)
                    this.loading = false
                })
            },

            pick (opt) {
                this.selected = opt
                this.$dispatch('input', opt.id)
                this.close()
            },

            open () {
                document.documentElement.classList.add('overflow-hidden')
                this.options = []
                this.text = null
                this.show = true
                this.fetch()
            },
            
            close () {
                document.documentElement.classList.remove('overflow-hidden')
                this.show = false
            },
        }))
    })
</script>
