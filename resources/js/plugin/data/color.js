import { computePosition, flip, shift, offset } from '@floating-ui/dom'

export default (config) => {
    return {
        value: config.value,
        visible: false,

        show () {
            if (this.visible) return
            this.visible = true
            this.$nextTick(() => this.positioning())
        },

        close () {
            this.visible = false
        },

        positioning () {
            let anchor = this.$refs.trigger
            let body = this.$refs.options

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
    }
}