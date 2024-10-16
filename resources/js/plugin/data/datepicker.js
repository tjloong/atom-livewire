import Pikaday from 'pikaday'
import { computePosition, flip, shift, offset } from '@floating-ui/dom'

export default (config) => {
    return {
        value: config.value,
        time: [],
        picker: null,
        visible: false,
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
            if (this.visible) return

            this.visible = true

            this.$nextTick(() => {
                this.initPikaday()
                this.$refs.calendar.addClass('opacity-100')

                setTimeout(() => {
                    this.positioning()
                    this.setRange()
                }, 50)
            })
        },

        close () {
            this.$refs.calendar.removeClass('opacity-100')

            setTimeout(() => {
                this.visible = false

                if (this.picker) {
                    this.picker[0]?.destroy()
                    this.picker[1]?.destroy()
                }
            }, 75)
        },

        select () {
            let format = 'YYYY-MM-DD HH:mm:ss'
            let one = this.picker[0]?.getDate()
            let two = this.picker[1]?.getDate()

            if (this.time[0] && one) one = `${one.getFullYear()}-${one.getMonth() + 1}-${one.getDate()} ${this.time[0]}`
            if (this.time[1] && two) two = `${two.getFullYear()}-${two.getMonth() + 1}-${two.getDate()} ${this.time[1]}`

            one = dayjs(one)
            two = dayjs(two)

            if (one.isValid()) {
                one = this.config.utc ? one.utc().format(format) : one.format(format)

                if (this.config.range) {
                    if (two.isValid()) {
                        two = this.config.utc ? two.utc().format(format) : two.format(format)
                        this.$refs.trigger.dispatch('input', `${one} to ${two}`)
                    }
                }
                else {
                    this.$refs.trigger.dispatch('input', one)
                }
            }
        },

        clear () {
            this.$refs.trigger.dispatch('input', '')
        },

        initPikaday () {
            let sel = this.getSelected()

            this.picker = []
            this.picker.push(new Pikaday({
                defaultDate: sel[0]?.toDate(),
                setDefaultDate: !empty(sel[0]),
                onSelect: () => this.select()
            }))

            if (this.config.range) {
                this.picker.push(new Pikaday({
                    defaultDate: sel[1]?.toDate(),
                    setDefaultDate: !empty(sel[1]),
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
            let one = this.config.range ? this.value?.split(' to ')[0] : this.value
            let two = this.config.range ? this.value?.split(' to ')[1] : null

            if (one) one = this.config.utc ? dayjs(one).utc(true).local() : null
            if (two) two = this.config.utc ? dayjs(two).utc(true).local() : null

            if (one?.isValid() && two?.isValid()) return [one, two]
            if (one?.isValid()) return [one]

            return []
        },

        positioning () {
            let anchor = this.$refs.trigger
            let body = this.$refs.calendar

            computePosition(anchor, body, {
                placement: 'bottom-start',
                middleware: [offset(4), flip(), shift({ padding: 5 })],
            }).then(({x, y}) => {
                Object.assign(body.style, {
                    left: `${x}px`,
                    top: `${y}px`,
                });
            });
        },

        setRange () {
            if (!this.visible) return
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