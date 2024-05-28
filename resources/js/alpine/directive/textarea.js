export default (el, { modifiers, expression }, { evaluate, effect }) => {
    if (!modifiers.includes('autoresize')) return

    let resize = function() {
        let styles = getComputedStyle(el)
        let lines = el.value.split(/\r\n|\r|\n/).length
        let lineHeight = parseInt(styles.lineHeight) || 1
        let height = (lines * lineHeight) + 10
        el.style.height = 'auto'
        el.style.height = height+'px'
    }

    effect(() => resize())
    el.addEventListener('focus', () => resize())
    el.addEventListener('input', () => resize())
}