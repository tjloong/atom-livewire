import '@atom/resources/js/atom.js'
import axios from 'axios'
import Alpine from 'alpinejs'
import Hooks from '@ryangjchandler/alpine-hooks'
import Tooltip from '@atom/resources/js/tooltip'

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.Alpine = Alpine
Alpine.plugin(Hooks)
Alpine.directive('tooltip', Tooltip)
Alpine.start()
