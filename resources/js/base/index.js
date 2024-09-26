import './prototype/array.js'
import './prototype/number.js'
import './prototype/string.js'
import './prototype/element.js'
import './helper/function.js'

import { ulid } from 'ulid'
import Ajax from './helper/ajax.js'
import Color from './helper/color.js'
import Toast from './helper/toast.js'
import Uploader from './helper/uploader.js'

window.dd = console.log.bind(console)

window.atom = {
    ulid: ulid,
    toast: Toast,
    ajax: (url) => (new Ajax(url)),
    color: (name) => new Color(name),
    goto: (url) => window.location = url,
    newtab: (url) => window.open(url, '_blank'),
    upload: (files, config) => (new Uploader(files, config)),
}
