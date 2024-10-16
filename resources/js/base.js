import './prototype/array.js'
import './prototype/number.js'
import './prototype/string.js'
import './prototype/element.js'

import Atom from './atom'
import Alpine from './alpine.js'

window.dd = console.log.bind(console)
window.Atom = Atom
window.Alpine = Alpine

// for faster access
window.t = Atom.t
window.empty = Atom.empty
