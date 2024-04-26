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