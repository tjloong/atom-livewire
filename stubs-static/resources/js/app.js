import '@atom/resources/js/atom.js'
import Alpine from 'alpinejs'
import Tooltip from '@atom/resources/js/directives/tooltip'

window.Alpine = Alpine
Alpine.directive('tooltip', Tooltip)
Alpine.start()
