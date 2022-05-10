<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formDateRange', (config) => ({
            uid: config.uid,
            show: false,
            calendar: null,
            settings: config.settings,
            value: [],

            get from () {
                const from = (this.value || [])[0]
                return formatDate(from)
            },

            get to () {
                const to = (this.value || [])[1]
                return formatDate(to)
            },

            init () {
                this.setValue()
            },

            setFloat () {
                const anchor = this.$refs.anchor
                const dd = this.$refs.dd

                floatPositioning(anchor, dd, {
                    placement: 'bottom',
                    flip: true,
                    offset: 4,
                    shift: { padding: 5 },
                })
            },

            setCalendar () {
                if (!this.calendar) {
                    this.calendar = flatpickr(this.$refs.calendar, {
                        mode: 'range',
                        inline: true,
                        dateFormat: 'Y-m-d',
                        onChange: (selectedDate, dateStr) => {
                            [from, to] = selectedDate

                            if (from) from = dayjs(from).format('YYYY-MM-DD')
                            if (to) to = dayjs(to).format('YYYY-MM-DD')

                            this.value = [from, to].filter(Boolean)
                            this.close()
                        },
                        ...this.settings,
                    })
                }

                this.calendar.setDate(this.value || [])
            },

            setValue () {
                if (config.model) this.value = this.$wire.get(config.model)
                else if (config.value) this.value = config.value
            },

            open () {
                this.show = true

                this.$nextTick(() => {
                    this.setFloat()
                    this.setCalendar()
                })
            },

            close () {
                if (this.value.length === 2) {
                    this.$refs.input.dispatchEvent(new CustomEvent('updated', { detail: this.value }))
                    this.show = false
                    this.calendar.destroy()
                    this.calendar = null
                }
                else this.setFloat()
            },
        }))
    })
</script>
