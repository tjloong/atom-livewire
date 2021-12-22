export default (value) => ({
    value,
    formatted: null,

    init () {
        this.format()
    },

    format () {
        this.formatted = Number.isFinite(parseFloat(this.value))
            ? this.value.toLocaleString('en-US')
            : null
    },

    parseValue (e) {
        let val = e.target.value

        val = val.replace(/[^\d\.]+/g, '')
        val = val.replace(/(\..*)\./g, '$1')
        val = parseFloat(val)

        this.value = !val || !Number.isFinite(val) ? null : val
        this.format()
    }
})