import './prototype/array.js'
import './prototype/number.js'
import './prototype/string.js'
import './prototype/element.js'
import './helper/function.js'
import Ajax from './helper/ajax.js'
import Clipboard from './alpine/magic/clipboard.js'

window.dd = console.log.bind(console)
window.ulid = ULID?.ulid
window.ajax = (url) => (new Ajax(url))

window.dayjs?.extend(dayjs_plugin_utc)
window.dayjs?.extend(dayjs_plugin_relativeTime)

document.addEventListener('alpine:init', () => {
    Alpine.magic('clipboard', Clipboard)
})
