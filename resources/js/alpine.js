// base for alpine
// suitable to use anywhere with minimal setup

import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'
import collapse from '@alpinejs/collapse'
import sort from '@alpinejs/sort'
import mask from '@alpinejs/mask'
import Autosize from '@marcreichel/alpine-autosize';
import Recaptcha from './plugin/directive/recaptcha.js'

Alpine.plugin(intersect)
Alpine.plugin(collapse)
Alpine.plugin(sort)
Alpine.plugin(mask)
Alpine.plugin(Autosize)
Alpine.directive('recaptcha', Recaptcha)

export default Alpine
