export default ('tooltip', (el, { value, expression }) => {
    if (!expression) return

    // create the tooltip
    const tooltipEl = document.createElement('div')
    tooltipEl.setAttribute('class', 'absolute bg-black opacity-80 text-white text-xs rounded p-1.5 hidden')
    tooltipEl.innerHTML = expression

    el.classList.add('relative')
    el.append(tooltipEl)

    // event
    const showTooltip = () => {
        tooltipEl.classList.remove('hidden')

        floatPositioning(el, tooltipEl, {
            placement: value || 'top',
            offset: 6,
            flip: true,
            shift: { padding: 5 },
        })
    }

    const hideTooltip = () => {
        tooltipEl.classList.add('hidden')
    }

    el.addEventListener('mouseover', () => showTooltip())
    el.addEventListener('mouseout', () => hideTooltip())
})
