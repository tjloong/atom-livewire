import { computePosition, flip, shift, offset } from '@floating-ui/dom'

export default (config) => {
    return {
        value: config.value,
        options: config.options || [],
        text: null,
        pointer: null,
        visible: false,

        get filtered () {
            return this.options
                .map(opt => (typeof opt === 'string' ? { name: opt, email: opt } : opt))
                .filter(opt => !empty(opt.email))
                .filter(opt => {
                    let search = this.text ? (opt.email.toLowerCase().includes(this.text) || opt.name.toLowerCase().includes(this.text)) : true
                    let exists = (this.value || []).some(val => (val.email === opt.email))
                    return !exists && search
                })
        },

        init () {
            if (!this.value) this.value = []
        },

        open () {
            this.visible = true
            this.$nextTick(() => this.positioning())
        },

        close () {
            this.visible = false
            this.pointer = null
            if (this.text) this.select(this.text)
        },

        select (opt) {
            if (typeof opt === 'string') {
                opt.split(';')
                    .map(str => str.trim())
                    .forEach(str => this.select({ name: str, email: str }))
            }
            else {
                if (!opt.email) return
                this.value.push(opt)
                this.text = null
            }
        },

        remove (email) {
            let index = this.value.findIndexWhere('email', email)
            if (index > -1) this.value.splice(index, 1)
        },

        keyEnter () {
            if (this.filtered.length) {
                this.select(this.filtered[this.pointer || 0])
            }
            else if (this.text) {
                this.select(this.text)
            }
        },

        keyUp () {
            if (this.pointer === null) this.pointer = 0
            this.pointer--
            if (this.pointer < 0) this.pointer = 0
        },

        keyDown () {
            if (this.pointer === null) this.pointer = 0
            const max = this.filtered.length ? this.filtered.length - 1 : 0
            this.pointer++
            if (this.pointer > max) this.pointer = max
        },

        validate (val) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)
        },

        positioning () {
            let anchor = this.$refs.trigger
            let body = this.$refs.options

            computePosition(anchor, body, {
                placement: 'bottom',
                middleware: [offset(4), flip(), shift({ padding: 5 })],
            }).then(({x, y}) => {
                Object.assign(body.style, {
                    left: `${x}px`,
                    top: `${y}px`,
                });
            });
        },
    }
}