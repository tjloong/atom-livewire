import './prototype/array.js'
import './prototype/number.js'
import './prototype/string.js'
import './prototype/element.js'
import './helper/function.js'
import Ajax from './helper/ajax.js'
import Clipboard from './alpine/magic/clipboard.js'
import { ulid } from 'ulid'

// dayjs plugins
if (window.dayjs) {
    dayjs.extend(dayjs_plugin_utc)
    dayjs.extend(dayjs_plugin_relativeTime)
}

window.ulid = ulid
window.dd = console.log.bind(console)
window.ajax = (url) => (new Ajax(url))

document.addEventListener('alpine:init', () => {
    Alpine.magic('clipboard', Clipboard)
})
