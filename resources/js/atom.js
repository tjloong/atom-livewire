import './helpers.js'
import './script-loader.js'

import ImageInput from './components/input/image'
import Tooltip from './directives/tooltip'

Alpine.data('imageInput', ImageInput)
Alpine.directive('tooltip', Tooltip)
