export default class
{
    refEl
    floatEl
    arrowEl
    cleanup
    config = {
        placement: null,
        middleware: null,
        autoUpdate: false,
    }

    constructor (refEl, floatEl) {
        this.refEl = refEl
        this.floatEl = floatEl
    }

    placement (placement) {
        this.config.placement = placement
        return this
    }

    arrow (el) {
        const { arrow } = window.FloatingUIDOM

        if (!this.config.middleware) this.config.middleware = []

        this.arrowEl = el
        this.config.middleware.push(arrow({ element: el }))

        return this
    }

    middleware (middleware = 'default') {
        const { flip, shift, offset, autoPlacement } = window.FloatingUIDOM

        this.config.middleware = middleware === 'default' ? [
            flip(),
            shift({ padding: 10 }),
            offset(4),
        ] : middleware

        if (!this.config.placement) this.config.middleware.push(autoPlacement())

        return this
    }

    autoUpdate (bool = true) {
        this.config.autoUpdate = bool
        return this
    }

    compute () {
        if (!this.config.middleware) this.middleware()

        const { computePosition, autoUpdate } = window.FloatingUIDOM

        const updatePosition = () => {
            computePosition(this.refEl, this.floatEl, {
                placement: this.config.placement,
                middleware: this.config.middleware,
            }).then(({ x, y, middlewareData }) => {
                Object.assign(this.floatEl.style, {
                    left: `${x}px`,
                    top: `${y}px`,
                })

                if (middlewareData.arrow) {
                    Object.assign(this.arrowEl.style, {
                        left: x != null ? `${x}px` : '',
                        top: y != null ? `${y}px` : '',
                    });
                }
            })    
        }

        if (this.config.autoUpdate) this.cleanup = autoUpdate(this.refEl, this.floatEl, updatePosition)
        else updatePosition()

        return this
    }
}