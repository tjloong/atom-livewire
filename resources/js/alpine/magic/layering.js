class Layering
{
    el
    layers
    modals
    drawers

    constructor (el) {
        this.el = el
        this.layers = Array.from(document.querySelectorAll('.app-layout-layer.active'))
        this.modals = Array.from(document.querySelectorAll('.modal.active'))
        this.drawers = Array.from(document.querySelectorAll('.drawer.active'))
    }

    all () {
        return this.layers.concat(this.modals).concat(this.drawers)
    }

    zindex () {
        let layers = this.all()
        .map(layer => (window.getComputedStyle(layer).getPropertyValue('z-index')))
        .map(n => +n)

        let max = Math.max(...layers)

        this.el.style.zIndex = max + 1
    }

    isEmpty (category = null) {
        if (category) return !this[category].length
        else return !this.all().length
    }
}

export default (el) => {
    return new Layering(el)
}