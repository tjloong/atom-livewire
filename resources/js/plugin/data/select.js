import { computePosition, flip, shift, offset } from '@floating-ui/dom'

export default (config) => {
    return {
        value: config.value || null,
        multiple: config.multiple,
        text: null,
        visible: false,
        loading: false,
        selected: null,

        get options () {
            return Array.from(this.$refs.options.querySelectorAll('[data-atom-option]'))
        },

        get activeIndex () {
            return this.options.findIndex(node => (node.getAttribute('data-option-focus')))
        },

        get isEmpty () {
            return !this.selected || (Array.isArray(this.selected) && !this.selected.length)
        },

        init () {
            this.$watch('text', () => this.search())
            this.$watch('value', () => this.getSelected())

            if (this.value) {
                this.search().then(() => this.getSelected())
            }
        },

        open () {
            if (this.options?.length) {
                this.$refs.options.showPopover()
            }
            else {
                this.search()
                    .then(() => this.$refs.options.showPopover())
                    .then(() => this.$refs.search?.focus())
            }
        },

        close () {
            this.$refs.options.hidePopover()
        },

        clear () {
            this.value = this.multiple ? [] : ''
            this.$dispatch('input', this.value)
        },

        search () {
            if (config.name) {
                this.loading = true

                return this.$wire.getOptions(config.id, config.name, {
                    search: this.text,
                    value: this.value,
                    ...config.filters,
                }).then(() => this.loading = false)
            }
            else {
                return new Promise((resolve) => resolve())
            }
        },

        setWidth () {
            if (this.$refs.trigger.clientWidth > this.$refs.options.clientWidth) {
                this.$refs.options.style.width = this.$refs.trigger.clientWidth+'px'
            }
        },

        select (opt) {
            if (this.multiple) {
                if (this.isSelected(opt)) {
                    this.deselect(opt)
                }
                else {
                    this.value = [
                        ...(this.value || []),
                        ...[opt],
                    ]
                }
            }
            else {
                this.value = opt
            }

            this.$dispatch('input', this.value)
            this.$nextTick(() => {
                this.getSelected()
                this.close()
            })
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
            this.selected = this.multiple
                ? this.options
                    .filter(opt => (opt.getAttribute('data-option-selected')))
                    .map(opt => ({
                        value: opt.getAttribute('data-option-value'),
                        label: opt.getAttribute('data-option-label'),
                    }))
                : this.options
                    .filter(opt => (opt.getAttribute('data-option-selected')))[0]?.querySelector('[data-option-content]')
                    .innerHTML
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