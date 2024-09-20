import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'
import collapse from '@alpinejs/collapse'
import anchor from '@alpinejs/anchor'
import sort from '@alpinejs/sort'
import mask from '@alpinejs/mask'
import Hooks from '@ryangjchandler/alpine-hooks'
import Tooltip from "@ryangjchandler/alpine-tooltip"
import Autosize from '@marcreichel/alpine-autosize';
import Plugin from './plugin'

Alpine.plugin(intersect)
Alpine.plugin(collapse)
Alpine.plugin(anchor)
Alpine.plugin(sort)
Alpine.plugin(mask)
Alpine.plugin(Hooks)
Alpine.plugin(Tooltip)
Alpine.plugin(Autosize)
Alpine.plugin(Plugin)

window.Alpine = Alpine
Alpine.start()

