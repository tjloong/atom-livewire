import './prototype/array.js'
import './prototype/number.js'
import './prototype/string.js'
import './prototype/element.js'
import './helper/function.js'

import { ulid } from 'ulid'
import Ajax from './helper/ajax.js'
import Util from './helper/util.js'
import Color from './helper/color.js'
import Alert from './helper/alert.js'
import Modal from './helper/modal.js'
import Sheet from './helper/sheet.js'
import Toast from './helper/toast.js'
import Confirm from './helper/confirm.js'
import Uploader from './helper/uploader.js'

window.dd = console.log.bind(console)

window.Atom = {
    ulid: ulid,
    util: Util,
    alert: Alert,
    modal: Modal,
    sheet: Sheet,
    toast: Toast,
    confirm: Confirm,
    ajax: (url) => (new Ajax(url)),
    goto: (url) => window.location = url,
    color: (name) => new Color(name),
    newtab: (url) => window.open(url, '_blank'),
    upload: (files, config) => (new Uploader(files, config)),
    dispatch: (name, detail) => dispatchEvent(new CustomEvent(name, { bubbles: true, detail })),
}
