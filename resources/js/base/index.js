import './prototype/array.js'
import './prototype/number.js'
import './prototype/string.js'
import './prototype/element.js'
import './helper/function.js'

import { ulid } from 'ulid'

import Ajax from './helper/ajax.js'
import Color from './helper/color.js'
import Toast from './helper/toast.js'
import Dispatch from './helper/dispatch.js'
import Uploader from './helper/uploader.js'

window.dd = console.log.bind(console)
window.ulid = ulid
window.Dispatch = Dispatch
window.Toast = Toast
window.ajax = (url) => (new Ajax(url))
window.color = (name) => new Color(name)
window.href = (url) => window.location = url
window.upload = (files, config) => (new Uploader(files, config))
