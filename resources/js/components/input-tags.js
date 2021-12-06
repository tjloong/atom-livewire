export default (value, options) => ({
    value,
    show: false,
    popper: null,
    options: [],
    init () {
        this.options = options.map(opt => ({ ...opt, selected: this.value.includes(opt.value) }))
    },
    open () {
        this.show = true

        if (!this.popper) {
            setTimeout(() => this.popper = Popper.createPopper(this.$refs.input, this.$refs.dropdown), 20)
        }
    },
    close () {
        this.show = false
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
    },
})
