Array.prototype.pluck = function(attr) {
    return this.map(val => (val[attr]))
}

Array.prototype.unique = function (attr = null) {
    if (typeof attr === 'function') {
        let values = this.map(row => (attr(row)))

        return [...new Set(values)].map(val => (
            this.find(row => (attr(row) == val))
        ))
    }
    else if (attr) {
        const values = this
            .map(row => (row[attr]))
            .map(val => (this.find(row => (row[attr] == val))))

        return [...new Set(values)]
    }

    return [...new Set([...this])]
}

Array.prototype.sum = function (attr = null) {
    return this.reduce((acc, value) => {
        if (typeof attr === 'function') value = attr(value)
        else if (attr) value = value[attr]

        return value + acc
    }, 0)
}

Array.prototype.toggle = function(value) {
    const index = this.indexOf(value)
    
    if (index === -1) this.push(value)
    else this.splice(index, 1)
    
    return this
}

Array.prototype.take = function(n) {
    let array = [...this]
    return array.slice(0, n)
}