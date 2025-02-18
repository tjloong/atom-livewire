import { computePosition, flip, shift, offset } from '@floating-ui/dom'

export default (config) => {
    return {
        value: config.value || null,
        options: [],
        callback: typeof config.options === 'string' ? config.options : null,
        multiple: config.multiple,
        text: null,
        visible: false,
        loading: false,
        selected: null,

        get isEmpty () {
            return !this.selected || (Array.isArray(this.selected) && !this.selected.length)
        },

        get searchable () {
            return config.searchable && (
                !empty(this.text)
                || (Array.isArray(this.options) && this.options.length > 0)
                || !empty(this.callback)
            )
        },

        init () {
            this.$watch('text', () => this.fetch())
            this.$watch('value', () => this.$nextTick(() => this.getSelected()))
            this.$nextTick(() => {
                if ((this.multiple && this.value?.length) || (!this.multiple && this.value)) {
                    this.fetch().then(() => this.getSelected())
                }
            })
        },

        open () {
            this.fetch()
                .then(() => this.$refs.options.showPopover())
                .then(() => this.$refs.search?.focus())
                .then(() => this.visible = true)
        },

        close () {
            this.$refs.options.hidePopover()
            this.text = null
            this.visible = false
            this.loading = false
        },

        clear () {
            this.value = this.multiple ? [] : ''
            this.$dispatch('input', this.value)
        },

        fetch () {
            if (this.callback) {
                this.loading = true
                return Atom.action('get-options', { name: this.callback, filters: {
                    search: this.text,
                    value: this.value,
                    ...config.filters,
                }}).then(res => this.options = [...res]).then(() => this.loading = false)
            }
            else {
                return new Promise((resolve) => {
                    this.options = [...(config.options || [])]

                    if (this.text && this.options.length) {
                        this.options = this.options.filter(opt => (opt.label.toLowerCase().includes(this.text.toLowerCase())))
                    }

                    resolve()
                })
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
            this.$nextTick(() => this.close())
        },

        deselect (opt) {
            let values = [...this.value]
            let index = values.findIndex(val => (val == opt))

            if (index > -1) {
                values.splice(index, 1)
                this.$el.removeAttribute('data-option-selected')
                this.$refs.trigger.dispatch('input', values)
                this.value = [...values]
            }
        },

        moveTo (el, focus = true) {
            if (focus) {
                let focused = this.getFocusedElementIndex()
                if (focused > -1) this.moveTo(this.getOptionsElements(focused), false)
                el.setAttribute('data-option-focus')
            }
            else {
                el.removeAttribute('data-option-focus')
            }
        },

        getOptionsElements (index = -1) {
            let els = Array.from(this.$refs.options.querySelectorAll('[data-atom-option]'))
            return index > -1 ? els[index] : els
        },

        getFocusedElementIndex () {
            return this.getOptionsElements().findIndex(node => (node.getAttribute('data-option-focus')))
        },

        keyUp () {
            if (!this.visible) this.open()
            else {
                let els = this.getOptionsElements()
                let active = this.getFocusedElementIndex()
                let prev = active <= 0 ? (els.length - 1) : (active - 1)
                if (prev > -1) {
                    this.moveTo(els[prev])
                    this.scroll()
                }
            }
        },

        keyDown () {
            if (!this.visible) this.open()
            else {
                let els = this.getOptionsElements()
                let active = this.getFocusedElementIndex()
                let next = active >= els.length - 1 ? 0 : (active + 1)
                if (next > -1) {
                    this.moveTo(els[next])
                    this.scroll()
                }
            }
        },

        keyEnter () {
            if (!this.visible) this.open()
            else {
                let els = this.getOptionsElements()
                let active = this.getFocusedElementIndex()
                if (active > -1) els[active].querySelector('* > div').click()
            }
        },

        isSelected (value) {
            return this.multiple
                ? (this.value || []).includes(value)
                : value === this.value
        },

        getSelected () {
            let els = this.getOptionsElements()
                .map(node => (node.querySelector('* > div')))
                .filter(node => {
                    let nodeValue = node.getAttribute('data-option-value')
                    return Array.isArray(this.value) ? this.value.includes(nodeValue) : this.value === nodeValue
                })
                .map(node => ({
                    value: node.getAttribute('data-option-value'),
                    label: node.querySelector('[data-option-label]')?.innerHTML,
                    badge: node.querySelector('[data-option-badge]')?.innerHTML,
                    caption: node.querySelector('[data-option-caption]')?.innerHTML,
                    color: node.querySelector('[data-option-color]')?.style?.backgroundColor,
                    note: node.querySelector('[data-option-note]')?.innerHTML,
                    avatar: node.querySelector('[data-option-avatar]')?.innerHTML,
                    content: node.querySelector('[data-option-content]')?.innerHTML,
                }))

            this.selected = this.multiple ? els : els[0]
        },

        scroll () {
            let ul = this.$refs.options.querySelector('ul')
            let els = this.getOptionsElements()
            let index = els.findIndex(node => (node.getAttribute('data-option-focus', true)))
            let focus = index > -1 ? els[index] : null

            if (!focus) return

            if (index === 0) ul.scrollTop = 0
            else if (index === els.length - 1) ul.scrollTop = ul.scrollHeight
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