// dayjs plugins
if (window.dayjs) {
    dayjs.extend(dayjs_plugin_utc)
    dayjs.extend(dayjs_plugin_relativeTime)
}

import './helpers.js'

import Alpine from 'alpinejs'
window.Alpine = Alpine

import Tooltip from './directives/tooltip'
Alpine.directive('tooltip', Tooltip)

