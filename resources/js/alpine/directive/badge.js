function setBadgeClass(child, badge, modifiers) {
    child.addClass('px-2 inline-block font-medium rounded-md')

    if (modifiers.includes('lowercase') || modifiers.includes('lower')) child.addClass('lowercase')
    else if (modifiers.includes('uppercase') || modifiers.includes('upper')) child.addClass('uppercase')

    if (modifiers.includes('xs')) child.addClass('text-xs')
    else if (modifiers.includes('md')) child.addClass('text-base')
    else if (modifiers.includes('lg')) child.addClass('text-lg')
    else child.addClass('text-sm')
}

function setBadgeStyle(child, badge, modifiers) {
    let solid = modifiers.includes('solid')

    child.style.backgroundColor = solid ? color(badge.color).value() : color(badge.color).inverted().value()
    child.style.color = solid ? color(badge.color).inverted().value() : color(badge.color).value()
    child.style.border = `1px solid ${color(badge.color).value()}`
}

export default (el, { modifiers, expression }, { Alpine, evaluate, effect }) => {
    let params = modifiers.includes('raw') ? expression : evaluate(expression)

    let badges = (Array.isArray(params) ? params : [params]).map(val => {
        if (typeof val === 'string') return { label: val, color: 'gray' }
        else if (Object.keys(val).includes('label')) return { label: val.label, color: val.color || 'gray' }
        else {
            return Object.keys(val).map(key => ({
                label: val[key],
                color: key,
            }))
        }
    }).flat()

    el.innerHTML = ''

    if (badges.length > 1) {
        el.addClass('inline-flex items-center gap-2 flex-wrap')
    }

    badges.forEach(badge => {
        let child = document.createElement('div')

        setBadgeClass(child, badge, modifiers)
        setBadgeStyle(child, badge, modifiers)

        child.textContent = badge.label
        el.append(child)
    })
}