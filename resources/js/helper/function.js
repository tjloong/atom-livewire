// check for emptyness
window.empty = (value) => {
    if (value === undefined || value === null) return true

    value = JSON.parse(JSON.stringify(value))

    return (Array.isArray(value) && !value.length)
        || (typeof value === 'object' && !Object.keys(value).length && Object.getPrototypeOf(value) === Object.prototype)
        || (typeof value === 'string' && value.trim() === '')
}

// check page is reloaded
window.isPageReloaded = () => {
    return (window.performance.navigation && window.performance.navigation.type === 1)
        || window.performance.getEntriesByType('navigation').map((nav) => nav.type).includes('reload')
}

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

// screen size
window.screensize = (size = null) => {
    const w = window.innerWidth
    let value

    if (w <= 640) value = 'sm'
    if (w > 640 && w <= 768) value = 'md'
    if (w > 768 && w <= 1024) value = 'lg'
    if (w > 1024 && w <= 1280) value = 'xl'
    if (w > 1280 && w <= 1536) value = '2xl'
    if (w > 1536 && w <= 2000) value = '3xl'

    if (size) return [size].flat().includes(value)
    else return value
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

// generate random string
window.random = () => {
    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
}

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
window.floatDropdown = (anchor, dropdown, placement = 'bottom') => {
    const { computePosition, flip, shift, offset } = window.FloatingUIDOM
    
    computePosition(anchor, dropdown, {
        placement,
        middleware: [flip(), shift({ padding: 10 }), offset(4)],
    }).then(({x, y}) => {
        Object.assign(dropdown.style, { left: `${x}px`, top: `${y}px` })
    })
}
