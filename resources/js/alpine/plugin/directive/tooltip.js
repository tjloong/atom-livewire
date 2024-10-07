function createTooltip (content, modifiers) {
    let div = document.createElement('div')

    div.addClass('absolute bg-black/80 text-zinc-100 w-max whitespace-nowrap rounded-md px-3 py-1 shadow text-sm opacity-0 transition-opacity duration-100 delay-100')

    if (!modifiers.length || modifiers.includes('top')) div.addClass('bottom-full left-1/2 -translate-x-1/2')
    else if (modifiers.includes('bottom')) div.addClass('top-full left-1/2 -translate-x-1/2')
    else if (modifiers.includes('left')) div.addClass('right-full top-1/2 -translate-y-1/2')
    else if (modifiers.includes('right')) div.addClass('left-full top-1/2 -translate-y-1/2')

    div.show = () => {
        div.addClass('opacity-100')
        div.style.zIndex = 999
    }

    div.hide = () => {
        div.removeClass('opacity-100')
        div.style.zIndex = 0
    }

    div.innerHTML = content

    return div
}

export default (el, { modifiers, expression }, { evaluate, evaluateLater }) => {
    let content = evaluate(expression)
    if (content === el.innerHTML.striptags().trim()) return

    let tooltip = createTooltip(content, modifiers)

    el.addClass('relative')
    el.append(tooltip)

    el.addEventListener('mouseover', () => tooltip.show())
    el.addEventListener('mouseout', () => tooltip.hide())
}