// rgb to hex
window.rgbToHex = (r, g, b) => {
    return "#" + (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1);
}

// hex to rgb
window.hexToRgb = (hex) => {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    const shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

// format file size
window.formatFilesize = (value) => {
    let n = value/1024

    if (n >= 1024) return `${(n/1024).toFixed(2)} MB`
    else return `${n.toFixed(2)} KB`
}

// check for emptyness
window.empty = (value) => {
    if (value === undefined || value === null) return true

    value = JSON.parse(JSON.stringify(value))

    return (Array.isArray(value) && !value.length)
        || (typeof value === 'object' && !Object.keys(value).length && Object.getPrototypeOf(value) === Object.prototype)
}

// short number
window.shortNumber = (n) => {
    return Intl.NumberFormat('en-US', {
        notation: "compact",
        maximumFractionDigits: 1
    }).format(n);
}

// format date
window.formatDate = (value, option) => {
    const date = dayjs(value, 'YYYY-MM-DD HH:mm:ss')
    const format = {
        time: 'h:mm A',
        date: 'DD MMM, YYYY',
        datetime: 'DD MMM, YYYY - h:mm A',
    }

    if (date.isValid()) {
        if (option && option.format) return date.format(option.format)
        else if (option == 'fromNow') return date.fromNow()
        else if (option == 'datetime') return date.format(format.datetime)
        else if (option == 'time') return date.format(format.time)
        else return date.format(format.date)
    }

    return value
}

// format currency
window.currency = (val, symbol = null, round = true) => {
    const config = { minimumFractionDigits: 2 }

    if (symbol) {
        config.currency = symbol
        config.style = 'currency'
    }

    let num = Number(val)

    if (round) {
        num = num + Number.EPSILON
        const rounded = Math.round(num * 2 * 10)/10/2
        return rounded.toLocaleString('en-US', config)
    }
    else return num.toLocaleString('en-US', config)
}

// device type
window.deviceType = () => {
    const ua = navigator.userAgent

    if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
        return 'tablet'
    }
    else if (/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
        return 'mobile'
    }

    return 'desktop'
}

// get youtube vid
window.getYoutubeVid = (url) => {
    const regex = /(?:youtube(?:-nocookie)?\.com\/(?:[^/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?/ ]{11})/
    const matches = url.match(regex)

    return matches ? matches[1] : null
}

// generate random string
window.random = () => {
    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
}

// dd
window.dd = console.log.bind(console)

// floating element positioning
window.floatPositioning = (refEl, floatEl, config = { placement: 'bottom' }) => {
    const { computePosition, flip, shift, offset, autoPlacement } = window.FloatingUIDOM

    const middleware = []
    if (config.offset) middleware.push(offset(config.offset))
    if (config.flip) middleware.push(flip())
    if (config.shift) middleware.push(shift(config.shift))
    if (!config.placement) middleware.push(autoPlacement())

    const options = {}
    if (config.placement) options.placement = config.placement
    if (middleware.length) options.middleware = middleware

    computePosition(refEl, floatEl, options).then(({x, y}) => {
        Object.assign(floatEl.style, { left: `${x}px`, top: `${y}px` })
    })
}

// floating for dropdown
window.floatDropdown = (anchor, dropdown) => {
    const { computePosition, flip, shift, offset } = window.FloatingUIDOM
    
    computePosition(anchor, dropdown, {
        placement: 'bottom',
        middleware: [flip(), shift({ padding: 10 }), offset(4)],
    }).then(({x, y}) => {
        Object.assign(dropdown.style, { left: `${x}px`, top: `${y}px` })
    })
}

// toggle element in array
window.arrayToggle = (array, value) => {
    const index = array.indexOf(value)

    if (index === -1) array.push(value)
    else array.splice(index, 1)

    return array
}
