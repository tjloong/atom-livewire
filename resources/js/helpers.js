const dayjs = require('@node/dayjs')
const relativeTime = require('@node/dayjs/plugin/relativeTime')

dayjs.extend(relativeTime)

// format date
global.formatDate = (value, option) => {
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

// device type
global.deviceType = () => {
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
global.getYoutubeVid = (url) => {
    const regex = /(?:youtube(?:-nocookie)?\.com\/(?:[^/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?/ ]{11})/
    const matches = url.match(regex)

    return matches ? matches[1] : null
}

// generate random string
global.random = () => {
    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15)
}

// dd
global.dd = console.log.bind(console)

// floating element positioning
global.floatPositioning = (refEl, floatEl, config = { placement: 'bottom' }) => {
    return ScriptLoader.load([
        'https://unpkg.com/@floating-ui/core@0.1.2/dist/floating-ui.core.min.js',
        'https://unpkg.com/@floating-ui/dom@0.1.2/dist/floating-ui.dom.min.js',
    ]).then(() => {
        return new Promise(resolve => setTimeout(() => {
            const { computePosition, flip, shift, offset, autoPlacement } = FloatingUIDOM

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

            resolve()
        }, 50))
    })
}

// toggle element in array
global.arrayToggle = (array, value) => {
    const index = array.indexOf(value)

    if (index === -1) array.push(value)
    else array.splice(index, 1)

    return array
}
