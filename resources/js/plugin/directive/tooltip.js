export default (el, { modifiers, expression }, { evaluate, evaluateLater }) => {
    let tooltip = document.querySelector('[data-tooltip]')
    let content = evaluate(expression)

    if (!tooltip) return
    if (!content) return
    if (content === el.innerHTML.striptags().trim()) return

    el.addEventListener('mouseover', () => {
        tooltip.querySelector('* > div').innerHTML = content
        tooltip.showPopover()
        tooltip.anchorTo(el, { placement: `${modifiers[0] || 'top'}-center` })
    })
        
    el.addEventListener('mouseout', () => {
        tooltip.querySelector('* > div').innerHTML = ''
        tooltip.hidePopover()
    })
}