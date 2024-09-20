function showTooltip(el) {
    let tooltip = document.createElement('div')
    tooltip.addClass('clipboard-tooltip absolute -top-6 right-2 bg-black shadow py-1.5 px-3 rounded text-sm text-white')
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
            try {
                navigator.clipboard.writeText(subject)
            } catch (error) {
                console.error('Unable to copy to clipboard.')
            }

            resolve()
        })).then(() => {
            if (!tooltip) return
            showTooltip(el)
            setTimeout(() => hideTooltip(el), 400)
        })
    }
}