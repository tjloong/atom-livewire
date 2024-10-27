import { computePosition, flip, shift, offset } from '@floating-ui/dom'

export default (align = 'left') => {
    return {
        visible: false,

        open () {
            this.visible = true
            this.$nextTick(() => this.positioning())
        },

        close () {
            this.visible = false
        },

        positioning () {
            computePosition(this.$refs.trigger, this.$refs.content, {
                placement: align === 'right' ? 'bottom-end' : 'bottom-start',
                middleware: [offset(4), flip(), shift({ padding: 5 })],
            }).then(({x, y}) => {
                Object.assign(this.$refs.content.style, {
                    left: `${x}px`,
                    top: `${y}px`,
                });
            });
        },
    }
}