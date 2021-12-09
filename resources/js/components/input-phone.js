export default (value, countries, code = '+60') => ({
    value,
    countries,
    code,
    number: null,

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
        this.close()
    },
    open () {
        this.$refs.dropdown.classList.remove('hidden')
        this.$refs.dropdown.classList.add('opacity-0')

        floatPositioning(this.$refs.input, this.$refs.dropdown, {
            placement: 'bottom',
            flip: true,
        }).then(() => this.$refs.dropdown.classList.remove('opacity-0'))
    },
    close () {
        this.$refs.dropdown.classList.add('hidden')
    },
})