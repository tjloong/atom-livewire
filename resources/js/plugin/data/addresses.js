export default (config) => {
    return {
        values: config.values,
        address: null,

        initAddress (address) {
            this.address = {
                country: config.country,
                ...address,
            }
        },

        remove (value) {
            let index = this.values.findIndex(item => (this.getString(item) === this.getString(value)))
            if (index > -1) this.values.splice(index, 1)
        },

        getString (value) {
            let lines = [value.line_1, value.line_2, value.line_3].filter(Boolean).join(', ')
            let city = [value.postcode, value.city].filter(Boolean).join(' ')
            let country = value.country?.headline()
            let string = [lines, city, value.state, country].filter(Boolean).join(', ')

            return string.split(',')
                .map(s => (s.trim()))
                .unique()
                .filter(Boolean)
                .join(', ')
        },

        submit (address) {
            let index = address.id ? this.values.findIndexWhere('id', address.id) : -1

            let value = {
                id: address.id,
                line_1: address.line_1,
                line_2: address.line_2,
                line_3: address.line_3,
                postcode: address.postcode,
                city: address.city,
                state: address.state,
                country: address.country,
                notes: address.notes,
            }

            if (index > -1) this.values[index] = value
            else this.values.push(value)
        },
    }
}