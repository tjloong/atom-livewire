import './prototype/array.js'
import './prototype/number.js'
import './prototype/string.js'
import './prototype/element.js'
import './helper/function.js'
import Ajax from './helper/ajax.js'
import Float from './helper/float.js'
import Dropdown from './alpine/dropdown.js'
import Tooltip from './alpine/tooltip.js'
import { ulid } from 'ulid'

// dayjs plugins
if (window.dayjs) {
    dayjs.extend(dayjs_plugin_utc)
    dayjs.extend(dayjs_plugin_relativeTime)
}

window.ulid = ulid
window.dd = console.log.bind(console)
window.ajax = (url) => (new Ajax(url))
window.float = (refEl, floatEl) => (new Float(refEl, floatEl))

document.addEventListener('alpine:init', () => {
    Alpine.directive('dropdown', Dropdown)
    Alpine.directive('tooltip', Tooltip)
})
