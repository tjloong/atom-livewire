import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'
import collapse from '@alpinejs/collapse'
import anchor from '@alpinejs/anchor'
import sort from '@alpinejs/sort'
import mask from '@alpinejs/mask'
import Autosize from '@marcreichel/alpine-autosize';
import Hooks from '@ryangjchandler/alpine-hooks'
import Tooltip from "@ryangjchandler/alpine-tooltip"
import Plugin from './plugin'

Alpine.plugin(intersect)
Alpine.plugin(collapse)
Alpine.plugin(anchor)
Alpine.plugin(sort)
Alpine.plugin(mask)
Alpine.plugin(Autosize)
Alpine.plugin(Hooks)
Alpine.plugin(Tooltip)
Alpine.plugin(Plugin)

window.Alpine = Alpine
Alpine.start()
