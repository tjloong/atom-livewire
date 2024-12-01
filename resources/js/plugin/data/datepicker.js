import Pikaday from 'pikaday'

export default (config) => {
    return {
        value: config.value,
        time: [],
        picker: null,
        config: {
            utc: config.utc,
            range: config.range,
            time: config.time,
        },

        init () {
            this.$watch('value', () => this.setRange())
            this.initTime()
            this.$watch('time', () => this.select())
        },

        open () {
            if (this.$refs.popover?.hasAttribute('data-open')) return

            this.close()
            this.$refs.popover.showPopover()

            setTimeout(() => {
                this.initPikaday()
                this.setRange()
            }, 50)
        },

        close () {
            this.$refs.popover.hidePopover()
            
            if (this.picker) {
                this.picker[0]?.destroy()
                this.picker[1]?.destroy()
            }
        },

        select () {
            let one = this.picker[0]?.getDate()
            let two = this.picker[1]?.getDate()

            if (this.time[0] && one) one = `${one.getFullYear()}-${one.getMonth() + 1}-${one.getDate()} ${this.time[0]}`
            if (this.time[1] && two) two = `${two.getFullYear()}-${two.getMonth() + 1}-${two.getDate()} ${this.time[1]}`

            one = dayjs(one)
            two = dayjs(two)

            if (one.isValid()) {
                one = this.config.utc ? one.utc().toISOString() : one.toISOString()

                if (this.config.range) {
                    if (two.isValid()) {
                        two = this.config.utc ? two.utc().toISOString() : two.toISOString()
                        this.value = `${one} to ${two}`
                        this.$dispatch('input', this.value)
                    }
                }
                else {
                    this.value = one
                    this.$dispatch('input', this.value)
                }
            }
        },

        selectCustomRange (from, to) {
            from = this.config.utc ? from.utc().toISOString() : from.toISOString()
            to = this.config.utc ? to.utc().toISOString() : to.toISOString()
            this.value = `${from} to ${to}`
            this.$dispatch('input', this.value)
        },

        clear () {
            this.value = ''
            this.$dispatch('input', '')
        },

        initPikaday () {
            let sel = this.getSelected()

            this.picker = []
            this.picker.push(new Pikaday({
                defaultDate: sel[0]?.toDate(),
                setDefaultDate: !empty(sel[0]),
                keyboardInput: false,
                onSelect: () => this.select()
            }))

            if (this.config.range) {
                this.picker.push(new Pikaday({
                    defaultDate: sel[1]?.toDate() || dayjs().add(1, 'month').toDate(),
                    setDefaultDate: !empty(sel[1]),
                    keyboardInput: false,
                    onSelect: () => this.select(),
                }))

                this.$refs.from.prepend(this.picker[0].el)
                this.$refs.to.prepend(this.picker[1].el)
            }
            else {
                this.$refs.calendar.prepend(this.picker[0].el)
            }
        },

        initTime () {
            if (!this.config.time) return

            let sel = this.getSelected()

            this.time = sel.map(val => (val.format('hh:mm A')))
        },
 
        getSelected () {
            if (!this.value) return []

            let one = dayjs(this.config.range ? this.value?.split(' to ')[0] : this.value)
            let two = dayjs(this.config.range ? this.value?.split(' to ')[1] : null)

            if (one?.isValid() && two?.isValid()) return [one, two]
            if (one?.isValid()) return [one]

            return []
        },

        setRange () {
            if (!this.config.range) return
            if (!this.picker && !this.picker.length) return

            let start = this.picker[0]?.getDate()
            let end = this.picker[1]?.getDate()

            if (start && end) {
                this.picker[0].hide()
                this.picker[0].setStartRange(start)
                this.picker[0].setEndRange(end)
                this.picker[0].setMaxDate(end)

                this.picker[1].hide()
                this.picker[1].setStartRange(start)
                this.picker[1].setEndRange(end)
                this.picker[1].setMinDate(start)

                this.picker[0].show()
                this.picker[1].show()
            }
        },
   }
}