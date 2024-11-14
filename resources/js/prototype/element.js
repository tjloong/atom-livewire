import { computePosition, autoUpdate, flip, shift, offset } from '@floating-ui/dom'

Element.prototype.hasClass = function (name) {
    return this.classList.contains(name)
}

Element.prototype.addClass = function (...names) {
    names.forEach(name => {
        name.split(' ').forEach(val => this.classList.add(val))
    })
}

Element.prototype.removeClass = function (...names) {
    names.forEach(name => {
        name.split(' ').forEach(val => this.classList.remove(val))
    })
}

Element.prototype.toggleClass = function (...names) {
    names.forEach(name => {
        name.split(' ').forEach(val => this.hasClass(val)
            ? this.removeClass(val)
            : this.addClass(val)
        )
    })
}

Element.prototype.dispatch = function (name, data, bubbles = true) {
    this.dispatchEvent(new CustomEvent(name, { bubbles, detail: data }))
}

Element.prototype.anchorTo = function (anchor, config = {}) {
    let updatePosition = () => {
        computePosition(anchor, this, {
            placement: config.placement || 'bottom-start',
            middleware: [offset(config.offset || 2), flip(), shift({ padding: 5 })],
        }).then(({x, y}) => {
            Object.assign(this.style, { left: `${x}px`, top: `${y}px` })
        })
    }

    this.anchorCleanup = autoUpdate(anchor, this, updatePosition)
}
