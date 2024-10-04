import Tether from 'tether'

export default (align = 'bottom left') => {
    return {
        align, // bottom left, bottom right, bottom center, top left, top right, top center
        show: false,
        target: null,
        element: null,
        tether: null,

        init () {
            let children = this.$root.querySelectorAll(':scope > *')
            this.target = children[0] // anchor
            this.element = children[1]
            this.$nextTick(() => this.positioning())
        },

        open () {
            if (this.element.hasClass('hidden')) {
                this.element.removeClass('hidden')
                this.element.style.zIndex = Atom.util.highestZIndex() + 1
                setTimeout(() => this.element.addClass('opacity-100'), 20)
            }
            else this.close()
        },

        close () {
            this.element.removeClass('opacity-100')
            setTimeout(() => this.element.addClass('hidden'), 75)
        },

        positioning () {
            this.element.addClass('absolute transition-opacity duration-75 opacity-0 w-max mt-1')

            if (this.align === 'bottom left') this.element.addClass('top-full left-0')
            if (this.align === 'bottom center') this.element.addClass('top-full -left-1/2 -translate-x-1/2')
            if (this.align === 'bottom right') this.element.addClass('top-full right-0')
            if (this.align === 'top left') this.element.addClass('bottom-full left-0')
            if (this.align === 'top center') this.element.addClass('bottom-full -left-1/2 -translate-x-1/2')
            if (this.align === 'top right') this.element.addClass('bottom-full right-0')
        
            this.tether = new Tether({
                element: this.element,
                target: this.target,
                attachment: {
                    'bottom left': 'top left',
                    'bottom center': 'top center',
                    'bottom right': 'top right',
                    'top left': 'bottom left',
                    'top center': 'bottom center',
                    'top right': 'bottom right',
                }[this.align],
                targetAttachment: this.align,
                constraints: [{
                    to: 'window',
                    attachment: 'together',
                }],
            })

            this.close()
        },
    }
}