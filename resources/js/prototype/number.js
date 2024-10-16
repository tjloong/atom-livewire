Number.prototype.filesize = function() {
    let n = this/1024

    if (n >= 1024) return `${(n/1024).toFixed(2)} MB`
    else return `${n.toFixed(2)} KB`
}

Number.prototype.short = function() {
    return Intl.NumberFormat('en-US', {
        notation: "compact",
        maximumFractionDigits: 1
    }).format(this);
}

Number.prototype.currency = function(symbol = null, round = false) {
    const config = { minimumFractionDigits: 2 }

    let currency
    let num = Number(this)

    if (round) {
        num = num + Number.EPSILON
        const rounded = Math.round(num * 2 * 10)/10/2
        currency = rounded.toLocaleString('en-US', config)
    }
    else {
        currency = num.toLocaleString('en-US', config)
    }

    return symbol ? `${symbol} ${currency}` : `${currency}`
}

Number.prototype.decimalPlaces = function() {
    if (Math.floor(this.valueOf()) === this.valueOf()) return 0

    let str = this.toString()

    if (str.indexOf('.') !== -1 && str.indexOf('-') !== -1) return str.split('-')[1] || 0
    else if (str.indexOf('.') !== -1) return str.split('.')[1].length || 0

    return str.split('-')[1] || 0
}

Number.prototype.round = function(decimal = 2) {
    let weight = +('1'.padEnd(decimal + 1, '0'))
    return Math.round((this + Number.EPSILON) * weight) / weight
}