import { computePosition, flip, shift, offset } from '@floating-ui/dom'

export default (config) => {
    return {
        value: config.value,
        multiple: config.multiple,
        text: null,
        visible: false,

        get options () {
            return Array.from(this.$refs.options.querySelectorAll('[data-atom-option]'))
        },

        get activeIndex () {
            return this.options.findIndex(node => (node.getAttribute('data-option-focus')))
        },

        get isEmpty () {
            return !this.value || (Array.isArray(this.value) && !this.value.length) 
        },

        init () {
            this.$watch('text', () => this.search())
            if (this.value) this.search()
        },

        open () {
            this.visible = true

            if (config.name && !this.options.length) {
                this.search()
            }
            
            this.$nextTick(() => {
                this.$refs.search?.focus()
                this.$refs.options.addClass('opacity-100')
                setTimeout(() => this.positioning(), 50)
            })
        },

        close () {
            this.$refs.options.removeClass('opacity-100')
            setTimeout(() => this.visible = false, 75)
        },

        clear () {
            this.$dispatch('input', this.multiple ? [] : '')
        },

        search () {
            this.$wire.getOptions(config.id, config.name, {
                search: this.text,
                value: this.value,
                ...config.filters,
            })
        },

        select (opt) {
            if (this.multiple) {
                if (this.isSelected(opt)) this.deselect(opt)
                else {
                    this.$el.setAttribute('data-option-selected', true)
                    this.$refs.trigger.dispatch('input', [...(this.value || []), ...[opt]])
                }
            }
            else {
                this.options.forEach(node => node.removeAttribute('data-option-selected'))
                this.$el.setAttribute('data-option-selected', true)
                this.$refs.trigger.dispatch('input', opt)
            }

            this.close()
        },

        deselect (opt) {
            let values = [...this.value]
            let index = values.indexOf(opt)

            if (index > -1) {
                values.splice(index, 1)
                this.$el.removeAttribute('data-option-selected')
                this.$refs.trigger.dispatch('input', values)
            }
        },

        focus (el) {
            if (this.activeIndex > -1) this.blur(this.options[this.activeIndex])
            el.setAttribute('data-option-focus', true)
        },

        blur (el) {
            el.removeAttribute('data-option-focus')
        },

        keyUp () {
            if (!this.visible) this.open()
            else {
                let active = this.activeIndex
                let prev = active <= 0 ? (this.options.length - 1) : (active - 1)
                if (prev > -1) {
                    this.focus(this.options[prev])
                    this.scroll()
                }
            }
        },

        keyDown () {
            if (!this.visible) this.open()
            else {
                let active = this.activeIndex
                let next = active >= this.options.length - 1 ? 0 : (active + 1)
                if (next > -1) {
                    this.focus(this.options[next])
                    this.scroll()
                }
            }
        },

        keyEnter () {
            if (!this.visible) this.open()
            else if (this.activeIndex > -1) this.options[this.activeIndex].click()
        },

        isSelected (value) {
            return this.multiple
                ? (this.value || []).includes(value)
                : value === this.value
        },

        getSelected () {
            if (this.multiple) {
                return this.options
                    .filter(opt => (opt.getAttribute('data-option-selected')))
                    .map(opt => ({
                        value: opt.getAttribute('data-option-value'),
                        label: opt.getAttribute('data-option-label'),
                    }))
            }

            return this.options.filter(opt => (opt.getAttribute('data-option-selected')))[0]?.querySelector('[data-option-content]').innerHTML
        },

        scroll () {
            let ul = this.$refs.options.querySelector('ul')
            let index = this.options.findIndex(opt => (opt.getAttribute('data-option-focus', true)))
            let focus = index > -1 ? this.options[index] : null

            if (!focus) return

            if (index === 0) ul.scrollTop = 0
            else if (index === this.options.length - 1) ul.scrollTop = ul.scrollHeight
            else {
                let ceiling = 0
                let floor = ul.getBoundingClientRect().height
                let top = focus.getBoundingClientRect().top - ul.getBoundingClientRect().top
                let height = focus.getBoundingClientRect().height

                // sinked below floor, scroll down
                if (top > floor) ul.scrollTop = ul.scrollTop + (height * 2)
                // above scroll ceiling, scroll up
                else if (top < 0) ul.scrollTop = ul.scrollTop + top
            }
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