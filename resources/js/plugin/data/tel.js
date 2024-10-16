export default (config) => {
    return {
        value: config.value,
        code: config.code,
        tel: null,

        get options () {
            return Array.from(this.$refs.options.querySelectorAll('option'))
                .map(opt => (opt.getAttribute('value')))
        },

        init () {
            this.$nextTick(() => this.split())
            this.$watch('value', (val, old) => {
                if (val !== old) this.split()
            })
        },

        split () {
            if (!this.value) return

            let code = this.options.find(opt => (this.value.startsWith(opt)))
            let tel = code ? this.value.replace(code, '').replace('+', '') : this.value

            this.tel = tel || null
            this.code = code || null
        },

        format () {
            let code = this.code
            let tel = this.tel?.replace(/\s/g, '')

            if (!tel) return

            this.$refs.hidden.value = `${code}${tel}`
            this.$nextTick(() => this.$refs.hidden.dispatch('input'))
        },
    }
}