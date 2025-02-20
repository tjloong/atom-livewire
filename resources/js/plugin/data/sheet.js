export default (config) => {
    return {
        init () {
            if (!config.name) return console.error('Missing sheet name!')

            if (!window.sheet) window.sheet = { all: [], active: [] }

            window.sheet.all.push({
                name: config.name,
                label: config.label,
                el: this.$root,
                wire: this.$root.hasAttribute('wire:id') ? this.$wire : null,
            })

            if (!window.sheet.active.length) this.show(config.name)
        },

        setLabel (args) {
            if (args.name !== config.name) return

            let index = window.sheet.all.findIndexWhere('name', args.name)
            if (index > -1) window.sheet.all[index].label = args.label

            let activeIndex = window.sheet.active.findIndexWhere('name', args.name)
            if (activeIndex > -1) window.sheet.active[activeIndex].label = args.label

            Atom.dispatch('sheet-changed')
        },

        show (args) {
            let name = typeof args === 'string' ? args : args.name
            let label = typeof args === 'string' ? null : args.label
            let data = typeof args === 'string' ? null : args.data

            if (name !== config.name) return

            let index = name ? window.sheet.all.findIndexWhere('name', name) : 0
            let sheet = window.sheet.all[index]

            if (!sheet) return

            let active = window.sheet.active.firstWhere('name', name)

            if (active) {
                if (label) active.label = label
                this.moveTo(active).then(() => this.layering())
            }
            else {
                window.sheet.active.push({ ...sheet, label: label || sheet.label })
                this.layering()
                    .then(() => sheet.el.dispatch('open', data))
                    .then(() => Atom.dispatch('sheet-changed', data))
                    .then(() => Livewire.emit('sheetChanged', data))
            }
        },

        moveTo (sheet) {
            return new Promise((resolve, reject) => {
                if (+sheet.el.style.zIndex - 1 === window.sheet.active.lastIndex()) return

                let from = window.sheet.active.findIndexWhere('name', sheet.name) + 1
                let to = window.sheet.active.lastIndex()
                let removables = window.sheet.active.filter((s, i) => (i >= from && i <= to))

                removables.forEach((removable, i) => {
                    this.remove(removable)
                        .then(() => removable.el.dispatch('close'))
                        .then(() => removables.splice(i, 1))
                        .then(() => {
                            if (!removables.length) {
                                Atom.dispatch('sheet-changed')
                                resolve()
                            }
                        })
                })
            })
        },

        back () {
            if (window.sheet.active.lastIndex() !== +this.$root.style.zIndex - 1) return
            if (window.sheet.active.length <= 1) return

            let sheet = window.sheet.active.last()
            this.remove(sheet)
                .then(() => sheet.el.dispatch('close'))
                .then(() => Atom.dispatch('sheet-changed'))
                .then(() => this.layering())
        },

        refresh (args) {
            if (args.name !== config.name) return
            this.$wire.refresh()
        },

        remove (sheet) {
            return new Promise((resolve, reject) => {
                sheet.el.removeClass('opacity-100')
                sheet.el.removeAttribute('data-open')

                setTimeout(() => {
                    sheet.el.addClass('hidden')
                    let index = window.sheet.active.findIndexWhere('name', sheet.name)
                    window.sheet.active.splice(index, 1)
                    resolve()
                }, 200)
            })
        },

        scroll () {
            let navbar = document.querySelector('[data-atom-panel-navbar]')
            if (navbar) navbar.dispatch('transparent', this.$el.scrollTop < 20)
        },

        layering () {
            return new Promise((resolve, reject) => {
                window.sheet.active.forEach((sheet, index) => {
                    sheet.el.style.zIndex = index + 1
                    sheet.el.setAttribute('data-open', true)

                    if (index === window.sheet.active.lastIndex()) sheet.el.dispatch('awake')
                    else sheet.el.dispatch('sleep')

                    if (sheet.el.hasClass('hidden')) {
                        sheet.el.removeClass('hidden')
                        setTimeout(() => sheet.el.addClass('opacity-100'), 50)
                    }
                })

                resolve()
            })
        },
    }
}