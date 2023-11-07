import { ulid } from 'ulid'

// dayjs plugins
if (window.dayjs) {
    dayjs.extend(dayjs_plugin_utc)
    dayjs.extend(dayjs_plugin_relativeTime)
}

window.ulid = ulid

import './helpers.js'