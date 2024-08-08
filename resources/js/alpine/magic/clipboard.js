function showTooltip(el) {
    let tooltip = document.createElement('div')
    tooltip.addClass('clipboard-tooltip absolute -top-6 right-2 bg-black/50 py-1 px-2 rounded-md text-xs text-white')
    tooltip.innerHTML = tr('app.label.copied')

    el.addClass('relative')
    el.appendChild(tooltip)
}

function hideTooltip(el) {
    let tooltip = el.querySelector('.clipboard-tooltip')
    if (tooltip) el.removeChild(tooltip)
}

export default (el) => {
    return (subject, tooltip = true) => {
        return (new Promise((resolve, reject) => {
            navigator.clipboard.writeText(subject)
            resolve()
        })).then(() => {
            if (!tooltip) return
            showTooltip(el)
            setTimeout(() => hideTooltip(el), 400)
        })
    }
}