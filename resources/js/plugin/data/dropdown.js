import { computePosition, flip, shift, offset } from '@floating-ui/dom'

export default (align = 'left') => {
    return {
        show: false,
        menu: null,
        trigger: null,

        init () {
            let children = this.$root.querySelectorAll(':scope > *')
            this.trigger = children[0]
            this.menu = children[1]
            this.close()
        },

        open () {
            if (this.menu.hasClass('hidden')) {
                this.menu.removeClass('hidden')
                
                this.$nextTick(() => {
                    this.positioning()
                    this.menu.addClass('opacity-100')
                    this.$root.dispatch('open', null, false)
                })
            }
            else this.close()
        },

        close () {
            if (this.menu.hasClass('hidden')) return

            this.menu.addClass('opacity-0')
            this.menu.removeClass('opacity-100')

            setTimeout(() => {
                this.menu.addClass('hidden')
                this.$root.dispatch('close', null, false)
            }, 200)
        },

        positioning () {
            computePosition(this.trigger, this.menu, {
                placement: align === 'right' ? 'bottom-end' : 'bottom-start',
                middleware: [offset(4), flip(), shift({ padding: 5 })],
            }).then(({x, y}) => {
                Object.assign(this.menu.style, {
                    left: `${x}px`,
                    top: `${y}px`,
                });
            });
        },
    }
}