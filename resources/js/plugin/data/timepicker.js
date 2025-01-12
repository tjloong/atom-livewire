export default (config) => {
    return {
        value: config.value,
        hr: '00',
        min: '00',
        am: 'AM',

        init () {
            this.parse()
            this.$watch('value', (value, old) => { if (value !== old) this.parse() })
            this.$watch('hr', () => this.setTime())
            this.$watch('min', () => this.setTime())
            this.$watch('am', () => this.setTime())
        },

        parse () {
            let parser = dayjs('1970-01-01 '+this.value)
            this.hr = parser?.isValid() ? parser.format('hh') : '12'
            this.min = parser?.isValid() ? parser.format('mm') : '00'
            this.am = parser?.isValid() ? parser.format('A') : 'AM'
        },

        upHr () {
            this.hr = +this.hr < 12 ? (+this.hr + 1) : '00'
        },

        downHr () {
            this.hr = +this.hr > 0 ? (this.hr - 1) : '12'
        },

        upMin () {
            this.min = +this.min < 59 ? (+this.min + 1) : '00'
        },

        downMin () {
            this.min = +this.min > 0 ? (this.min - 1) : '59'
        },

        setAm () {
            this.am = this.am === 'AM' ? 'PM' : 'AM'
        },

        setTime () {
            this.hr = !+this.hr || this.hr > 12 ? '12' : this.hr.toString().padStart(2, '0')
            this.min = !+this.min || this.min > 59 ? '00' : this.min.toString().padStart(2, '0')
            this.value = `${this.hr}:${this.min} ${this.am}`
            this.$dispatch('input', this.value)
        },
    }
}