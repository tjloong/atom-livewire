export default (value, config) => ({
    value,
    fp: null,
    show: false,
    loading: false,

    open () {
        if (!window.flatpickr) this.loading = true

        ScriptLoader.load([
            { src: 'https://cdn.jsdelivr.net/npm/flatpickr', type: 'js' },
            { src: 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', type: 'css' },
        ]).then(() => {
            this.loading = false
            this.show = true

            this.fp = flatpickr(this.$refs.datepicker, {
                inline: true,
                dateFormat: 'Y-m-d',
                defaultDate: this.value,
                onClose: () => this.close(),
                onChange: (selectedDate, dateStr) => this.value = dateStr,
                ...config,
            })
        })
    },
    close () { 
        this.show = false
    },
    clear () {
        this.value = null
        this.$dispatch('input', null)
    },
})