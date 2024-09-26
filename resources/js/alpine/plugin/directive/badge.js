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

    child.style.backgroundColor = solid ? atom.color(badge.color).value() : atom.color(badge.color).inverted().value()
    child.style.color = solid ? atom.color(badge.color).inverted().value() : atom.color(badge.color).value()
    child.style.border = `1px solid ${atom.color(badge.color).value()}`
}

function createBadges(container, badges, modifiers) {
    container.innerHTML = ''

    if (badges.length > 1) {
        container.addClass('inline-flex items-center gap-2 flex-wrap')
    }

    badges.forEach(badge => {
        let child = document.createElement('div')

        setBadgeClass(child, badge, modifiers)
        setBadgeStyle(child, badge, modifiers)

        child.textContent = badge.label
        container.append(child)
    })
}

export default (el, { modifiers, expression }, { evaluateLater, effect }) => {
    if (modifiers.includes('raw')) {
        createBadges(el, [{ label: expression, color: 'gray' }], modifiers)
    }
    else {
        let getBadges = evaluateLater(expression)

        effect(() => {
            getBadges(res => {
                let badges = (Array.isArray(res) ? res : [res]).map(val => {
                    if (typeof val === 'string') return { label: val, color: 'gray' }
                    else if (Object.keys(val).includes('label')) return { label: val.label, color: val.color || 'gray' }
                    else {
                        return Object.keys(val).map(key => ({
                            label: val[key],
                            color: key,
                        }))
                    }
                }).flat()

                createBadges(el, badges, modifiers)
            })
        })
    }
}