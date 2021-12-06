export default ('tooltip', (el, { value, expression }) => {
    ScriptLoader.load([
        'https://unpkg.com/@floating-ui/core@0.1.2/dist/floating-ui.core.min.js',
        'https://unpkg.com/@floating-ui/dom@0.1.2/dist/floating-ui.dom.min.js',
    ]).then(() => {
        // create the tooltip
        const tooltipEl = document.createElement('div')
        tooltipEl.setAttribute('class', 'absolute bg-black opacity-80 text-white text-xs rounded p-1.5 hidden')
        tooltipEl.innerHTML = expression

        el.classList.add('relative')
        el.after(tooltipEl)

        // positioning
        const { computePosition, flip, shift, offset } = FloatingUIDOM
        const positioning = () => {
            computePosition(el, tooltipEl, {
                placement: value || 'top',
                middleware: [offset(6), flip(), shift({ padding: 5 })],
            }).then(({x, y}) => {
                Object.assign(tooltipEl.style, { left: `${x}px`, top: `${y}px` })
            })
        }

        // event
        el.addEventListener('mouseover', () => {
            tooltipEl.classList.remove('hidden')
            positioning()
        })

        el.addEventListener('mouseout', () => tooltipEl.classList.add('hidden'))
    })
})
