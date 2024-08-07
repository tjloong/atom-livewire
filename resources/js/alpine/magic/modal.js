// TODO: deprecate soon
class Modal
{
    el
    pages
    modals
    drawers

    constructor (el) {
        this.el = el
        this.modals = Array.from(document.querySelectorAll('.modal.active'))
        this.pages = Array.from(document.querySelectorAll('.modal-page.active'))
        this.drawers = Array.from(document.querySelectorAll('.modal-drawer.active'))
    }

    all () {
        return this.pages.concat(this.modals).concat(this.drawers)
    }

    zindex (el = null, n = null) {
        el = el || this.el

        if (n) el.style.zIndex = n
        else if (n === false) el.style.zindex = null
        else {
            let layers = this.all()
            .map(layer => (window.getComputedStyle(layer).getPropertyValue('z-index')))
            .map(n => +n)
    
            let max = Math.max(...layers)
    
            el.style.zIndex = max + 1
        }
    }

    lockScroll () {
        document.body.style.overflow = 'hidden'
    }

    unlockScroll () {
        document.body.style.overflow = 'auto'
    }

    isEmpty (category = null) {
        if (category) return !this[category].length
        else return !this.all().length
    }

    isActive (id) {
        return Array.from(
            document.querySelectorAll(`[data-modal-id="${id}"].active`)
        ).length > 0
    }
}

export default (el) => {
    return new Modal(el)
}