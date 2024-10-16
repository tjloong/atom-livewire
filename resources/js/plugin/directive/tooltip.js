import { computePosition, flip, shift, offset } from '@floating-ui/dom'

function createTooltip (content, modifiers) {
    let div = document.createElement('div')

    div.addClass('absolute top-0 left-0 px-3 py-1 rounded-md')
    div.addClass('bg-black/80 text-zinc-100 shadow text-sm w-max whitespace-nowrap')
    div.addClass('opacity-0 transition-opacity duration-75')

    div.innerHTML = content

    return div
}

function showTooltip (tooltip, placement) {
    if (tooltip.hasClass('opacity-100')) return

    tooltip.addClass('opacity-100')

    computePosition(tooltip.parentNode, tooltip, {
        placement,
        middleware: [offset(4), flip(), shift({ padding: 5 })],
    }).then(({x, y}) => {
        Object.assign(tooltip.style, {
            left: `${x}px`,
            top: `${y}px`,
            zIndex: 999,
        });
    });
}

function hideTooltip (tooltip) {
    tooltip.removeClass('opacity-100')
    Object.assign(tooltip.style, { zIndex: 0 })
}

export default (el, { modifiers, expression }, { evaluate, evaluateLater }) => {
    let content = evaluate(expression)
    if (content === el.innerHTML.striptags().trim()) return

    let tooltip = createTooltip(content, modifiers)

    el.append(tooltip)
    el.addEventListener('mouseover', () => showTooltip(tooltip, modifiers[0] || 'top'))
    el.addEventListener('mouseout', () => hideTooltip(tooltip))
}