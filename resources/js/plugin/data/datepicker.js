import Pikaday from 'pikaday'

export default (config) => {
    return {
        value: config.value,

        config: {
            utc: config.utc,
            range: config.range,
            time: config.time,
            toggler: config.toggler,
        },

        picker: {
            from: { pikaday: null, date: null, time: null,  iso: null, display: null },
            to: { pikaday: null, date: null, time: null,  iso: null, display: null },
        },

        get display () {
            let from = this.picker.from.display
            let to = this.picker.to.display

            return this.config.range ? [from, to].filter(Boolean).join(' - ') : from
        },

        get shortcuts () {
            return {
                'today': [dayjs().startOf('day'), dayjs().endOf('day')],
                'yesterday': [dayjs().subtract(1, 'day').startOf('day'), dayjs().subtract(1, 'day').endOf('day')],
                'this-month': [dayjs().startOf('month').startOf('day'), dayjs().endOf('month').endOf('day')],
                'this-year': [dayjs().startOf('year').startOf('day'), dayjs().endOf('year').endOf('day')],
                'last-7-days': [dayjs().subtract(6, 'day').startOf('day'), dayjs().endOf('day')],
                'last-30-days': [dayjs().subtract(29, 'day').startOf('day'), dayjs().endOf('day')],
                'last-month': [dayjs().startOf('month').subtract(1, 'day').startOf('month').startOf('day'), dayjs().startOf('month').subtract(1, 'day').endOf('month').endOf('day')],
                'last-year': [dayjs().startOf('year').subtract(1, 'day').startOf('year').startOf('day'), dayjs().startOf('year').subtract(1, 'day').endOf('year').endOf('day')],
            }
        },

        init () {
            this.$watch('value', (val) => this.syncValueToPicker())
            this.$nextTick(() => this.syncValueToPicker())
        },

        open () {
            if (this.$refs.popover?.hasAttribute('data-open')) return

            this.$refs.popover.showPopover()
            this.initPikaday()

            setTimeout(() => this.setPikadayRange(), 50)
        },

        close () {
            this.$refs.popover.hidePopover()
            this.destroyPikaday()
        },

        clear () {
            this.value = ''
            this.syncValueToPicker()
            this.$dispatch('input', '')
        },

        selectShortcut (from, to) {
            from = dayjs(from).utc().toISOString()
            to = dayjs(to).utc().toISOString()
            this.value = `${from} to ${to}`
            this.syncValueToPicker()
            this.$dispatch('input', this.value)
        },

        syncValueToPicker () {
            let from = this.config.range ? this.value?.split(' to ')[0] : this.value
            let to = this.config.range ? this.value?.split(' to ')[1] : null
            let dates = {
                from: from ? dayjs(from) : null,
                to: to ? dayjs(to) : null,
            }

            Object.keys(dates).forEach(key => {
                if (dates[key]?.isValid()) {
                    this.picker[key].date = dates[key].format('YYYY-MM-DD')
                    this.picker[key].time = dates[key].format('HH:mm:ss')
                    this.picker[key].iso = dates[key].toISOString()
                    this.picker[key].display = [
                        dates[key].format('DD MMM YYYY'),
                        this.config.time ? dates[key].format('hh:mm A') : null
                    ].filter(Boolean).join(' ')

                    if (this.picker[key].pikaday && this.picker[key].pikaday?.toString() !== this.picker[key].date) {
                        this.picker[key].pikaday.setDate(this.picker[key].date)
                    }
                }
                else {
                    this.picker[key].date = null
                    this.picker[key].time = null
                    this.picker[key].iso = null
                    this.picker[key].display = null
                    this.picker[key].pikaday?.clear()
                }
            })

            this.setPikadayRange()
        },

        select () {
            let value

            let fromDate = this.picker.from.pikaday?.getDate()
            let fromTime, from

            let toDate = this.picker.to.pikaday?.getDate()
            let toTime, to

            if (!fromDate && !toDate) return

            if (fromDate) {
                fromTime = this.picker.from.time
                from = dayjs([`${fromDate.getFullYear()}-${fromDate.getMonth() + 1}-${fromDate.getDate()}`, fromTime].filter(Boolean).join(' '))
            }

            if (toDate) {
                toTime = this.picker.to.time
                to = dayjs([`${toDate.getFullYear()}-${toDate.getMonth() + 1}-${toDate.getDate()}`, toTime].filter(Boolean).join(' '))
            }

            if (from?.isValid()) {
                from = from.utc().toISOString()

                if (this.config.range) {
                    if (to?.isValid()) {
                        to = to.endOf('day').utc().toISOString()
                        value = `${from} to ${to}`
                    }
                }
                else {
                    value = from
                }

                if (value !== this.value) {
                    this.value = value
                    this.syncValueToPicker()
                    this.$dispatch('input', value)
                }
            }
        },

        initPikaday () {
            this.destroyPikaday()

            this.picker.from.pikaday = new Pikaday({
                defaultDate: this.picker.from.iso ? new Date(this.picker.from.iso) : dayjs().toDate(),
                setDefaultDate: !empty(this.picker.from.iso),
                keyboardInput: false,
                onSelect: () => this.select(),
                toString: (date) => (dayjs(date.toISOString()).format('YYYY-MM-DD')),
            })

            this.$refs.from.prepend(this.picker.from.pikaday.el)
            
            if (this.config.range) {
                this.picker.to.pikaday = new Pikaday({
                    defaultDate: this.picker.to.iso ? new Date(this.picker.to.iso) : dayjs().add(1, 'month').toDate(),
                    setDefaultDate: !empty(this.picker.to.iso),
                    keyboardInput: false,
                    onSelect: () => this.select(),
                    toString: (date) => (dayjs(date.toISOString()).format('YYYY-MM-DD')),
                })

                this.$refs.to.prepend(this.picker.to.pikaday.el)
            }
        },

        destroyPikaday () {
            this.picker.from.pikaday?.destroy()
            this.picker.to.pikaday?.destroy()
        },

        setPikadayRange () {
            let from = this.picker.from.pikaday
            let to = this.picker.to.pikaday

            if (!from && !to) return
            if (!this.config.range) return

            let start = from?.getDate()
            let end = to?.getDate()

            if (start && end) {
                from.hide()
                from.setStartRange(start)
                from.setEndRange(end)
                from.setMaxDate(end)

                to.hide()
                to.setStartRange(start)
                to.setEndRange(end)
                to.setMinDate(start)

                from.show()
                to.show()
            }
        },

        toggleRange () {
            this.$nextTick(() => {
                this.initPikaday()
                this.syncValueToPicker()
            })
        },
   }
}