Element.prototype.hasClass = function(name) {
    return this.classList.contains(name)
}

Element.prototype.addClass = function(...names) {
    names.forEach(name => {
        name.split(' ').forEach(val => this.classList.add(val))
    })
}

Element.prototype.removeClass = function(...names) {
    names.forEach(name => {
        name.split(' ').forEach(val => this.classList.remove(val))
    })
}

Element.prototype.toggleClass = function(...names) {
    names.forEach(name => {
        name.split(' ').forEach(val => this.hasClass(val)
            ? this.removeClass(val)
            : this.addClass(val)
        )
    })
}

Element.prototype.dispatch = function(name, data) {
    this.dispatchEvent(new CustomEvent(name, { bubbles: true, detail: data }))
}
