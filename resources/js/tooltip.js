export default ('tooltip', (el, { value, expression }, { cleanup }) => {
    if (!expression) return

    // create the tooltip
    const tooltipEl = document.createElement('div')
    tooltipEl.setAttribute('class', 'absolute tooltip hidden')
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

    el.addEventListener('mouseenter', showTooltip)
    el.addEventListener('mouseleave', hideTooltip)

    cleanup(() => {
        el.removeEventListener('mouseenter', showTooltip);
        el.removeEventListener('mouseleave', hideTooltip);
    })
})
