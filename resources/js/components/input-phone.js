export default (value, countries, code = '+60') => ({
    value,
    countries,
    code,
    show: false,
    number: null,
    popper: null,

    init () {
        if (this.value?.startsWith('+')) {
            const country = this.countries.find(val => (this.value.startsWith(val.code)))

            if (country) {
                this.code = country.code
                this.number = this.value.replace(country.code, '')
            }
        }
        else this.number = this.value
    },
    input () {
        this.value = this.number ? `${this.code}${this.number}` : null
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
})