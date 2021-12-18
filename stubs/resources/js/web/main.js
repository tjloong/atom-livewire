import 'boxicons'
import '@atom/resources/js/helpers.js'
import '@atom/resources/js/script-loader.js'
import Slider from '@atom/resources/js/builder/slider.js'
import InputPhone from '@atom/resources/js/components/input-phone.js'
import FullscreenLoader from '@atom/resources/js/components/fullscreen-loader.js'
import Tooltip from '@atom/resources/js/directives/tooltip.js'
import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.data('slider', Slider)
Alpine.data('inputPhone', InputPhone)
Alpine.data('fullscreenLoader', FullscreenLoader)
Alpine.directive('tooltip', Tooltip)
Alpine.start()
