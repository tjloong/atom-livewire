export default (el, { expression }, { effect }) => {
    if (el.tagName !== 'TEXTAREA') return

    function resize() {
        el.style.resize = 'none'

        if (empty(el.value)) el.style.height = '25px'
        else {
            el.style.height = 'auto'
            el.style.height = el.scrollHeight+'px'
        }
    }

    setTimeout(() => resize(), 50)
    effect(() => resize())
    el.addEventListener('input', () => resize())
}