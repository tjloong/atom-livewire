<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formDate', (config) => ({
            show: false,
            value: null,
            calendar: null,
            settings: config.settings,

            init () {
                this.setValue()
            },

            setValue () {
                if (config.model) this.value = this.$wire.get(config.model) || null
                else if (config.value) this.value = config.value || null
            },

            setCalendar () {
                if (!this.calendar) {
                    this.calendar = flatpickr(this.$refs.datepicker, {
                        inline: true,
                        dateFormat: 'Y-m-d',
                        onClose: () => this.close(),
                        onChange: (selectedDate, dateStr) => this.value = dateStr,
                        ...this.settings,
                    })
                }

                this.calendar.setDate(this.value)
            },

            open () {
                this.show = true

                this.$nextTick(() => {
                    this.setCalendar()
                })
            },

            close () { 
                this.show = false
            },

            clear () {
                this.value = null
                this.$dispatch('input', '')
            },
        }))
    })
</script>
