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
