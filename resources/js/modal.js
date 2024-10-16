import Alpine from 'alpinejs'

import Alert from './plugin/data/alert.js'
import Modal from './plugin/data/modal.js'
import Toast from './plugin/data/toast.js'
import Confirm from './plugin/data/confirm.js'

import AlertHelper from './plugin/helper/alert.js'
import ModalHelper from './plugin/helper/modal.js'
import ToastHelper from './plugin/helper/toast.js'
import ConfirmHelper from './plugin/helper/confirm.js'

Alpine.data('alert', Alert)
Alpine.data('modal', Modal)
Alpine.data('toast', Toast)
Alpine.data('confirm', Confirm)

Atom.alert = AlertHelper
Atom.modal = ModalHelper
Atom.toast = ToastHelper
Atom.confirm = ConfirmHelper
