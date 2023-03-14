import '@atom/resources/js/atom.js'
import axios from 'axios'
import Alpine from 'alpinejs'
import Tooltip from '@atom/resources/js/directives/tooltip'

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Alpine = Alpine
Alpine.directive('tooltip', Tooltip)
Alpine.start()
