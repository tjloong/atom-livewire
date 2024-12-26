import './modal.js'
import './editor.js'

import Alpine from 'alpinejs'
import { ulid } from 'ulid'

// data
import Tel from './plugin/data/tel.js'
import Chat from './plugin/data/chat.js'
import Color from './plugin/data/color.js'
import Email from './plugin/data/email.js'
import Sheet from './plugin/data/sheet.js'
import Select from './plugin/data/select.js'
import Lightbox from './plugin/data/lightbox.js'
import Uploader from './plugin/data/uploader.js'
import Addresses from './plugin/data/addresses.js'
import Breadcrumb from './plugin/data/breadcrumb.js'
import Datepicker from './plugin/data/datepicker.js'
import Timepicker from './plugin/data/timepicker.js'

// magic
import SortId from './plugin/magic/sortid.js'
import Clipboard from './plugin/magic/clipboard.js'

// directive
import Badge from './plugin/directive/badge.js'
import Chart from './plugin/directive/chart.js'
import WireOn from './plugin/directive/wire-on.js'
import Tooltip from './plugin/directive/tooltip.js'

// helpers
import ColorHelper from './atom/color.js'
import UploaderHelper from './atom/uploader.js'
import SheetHelper from './plugin/helper/sheet.js'

Alpine.data('tel', Tel)
Alpine.data('chat', Chat)
Alpine.data('color', Color)
Alpine.data('email', Email)
Alpine.data('sheet', Sheet)
Alpine.data('select', Select)
Alpine.data('lightbox', Lightbox)
Alpine.data('uploader', Uploader)
Alpine.data('addresses', Addresses)
Alpine.data('breadcrumb', Breadcrumb)
Alpine.data('datepicker', Datepicker)
Alpine.data('timepicker', Timepicker)

Alpine.magic('sortid', SortId)
Alpine.magic('clipboard', Clipboard)

Alpine.directive('badge', Badge)
Alpine.directive('chart', Chart)
Alpine.directive('wire-on', WireOn)
Alpine.directive('tooltip', Tooltip)

Atom.ulid = ulid
Atom.sheet = SheetHelper
Atom.color = (name) => new Color(name)
Atom.upload = (files, config) => (new UploaderHelper(files, config))
