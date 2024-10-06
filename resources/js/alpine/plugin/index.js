import Alpine from 'alpinejs'

// data
import Alert from './data/alert.js'
import Modal from './data/modal.js'
import Toast from './data/toast.js'
import Sheet from './data/sheet.js'
import Confirm from './data/confirm.js'
import Uploader from './data/uploader.js'
import Dropdown from './data/dropdown.js'
import Breadcrumb from './data/breadcrumb.js'
import Overlay from './data/overlay.js'

// magic
import SortId from './magic/sortid.js'
import Clipboard from './magic/clipboard.js'

// directive
import Badge from './directive/badge.js'
import WireOn from './directive/wire-on.js'
import Tooltip from './directive/tooltip.js'
import Recaptcha from './directive/recaptcha.js'

export default function (Alpine) {
    Alpine.data('alert', Alert)
    Alpine.data('modal', Modal)
    Alpine.data('toast', Toast)
    Alpine.data('sheet', Sheet)
    Alpine.data('confirm', Confirm)
    Alpine.data('overlay', Overlay)
    Alpine.data('dropdown', Dropdown)
    Alpine.data('breadcrumb', Breadcrumb)
    Alpine.data('uploader', Uploader)

    Alpine.magic('sortid', SortId)
    Alpine.magic('clipboard', Clipboard)

    Alpine.directive('badge', Badge)
    Alpine.directive('wire-on', WireOn)
    Alpine.directive('tooltip', Tooltip)
    Alpine.directive('recaptcha', Recaptcha)
}
