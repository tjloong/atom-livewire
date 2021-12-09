export default ('tooltip', (el, { value, expression }) => {
    // create the tooltip
    const tooltipEl = document.createElement('div')
    tooltipEl.setAttribute('class', 'absolute bg-black opacity-80 text-white text-xs rounded p-1.5 hidden')
    tooltipEl.innerHTML = expression

    el.classList.add('relative')
    el.after(tooltipEl)

    // event
    el.addEventListener('mouseover', () => {
        tooltipEl.classList.remove('hidden')
        tooltipEl.classList.add('opacity-0')

        floatPositioning(el, tooltipEl, {
            placement: value || 'top',
            offset: 6,
            flip: true,
            shift: { padding: 5 },
        }).then(() => tooltipEl.classList.remove('opacity-0'))
    })

    el.addEventListener('mouseout', () => tooltipEl.classList.add('hidden'))
})
