export default (el, { modifiers, expression }, { evaluate, cleanup }) => {
    let content = modifiers.includes('text') ? expression : evaluate(expression)
    let placement = 'top'

    if (empty(content)) return
    if (modifiers.includes('left')) placement = 'left'
    if (modifiers.includes('right')) placement = 'right'
    if (modifiers.includes('bottom')) placement = 'bottom'
    if (modifiers.includes('top')) placement = 'top'

    let container = document.createElement('div')
    container.addClass('absolute flex items-center justify-center hidden opacity-0 transform-opacity duration-300')

    let arrow = document.createElement('div')
    arrow.addClass('absolute w-2 h-2 bg-black rotate-45')

    let body = document.createElement('div')
    body.addClass('relative bg-black z-10 text-white text-sm rounded-md px-3 py-1')
    body.innerHTML = content
    body.appendChild(arrow)

    if (placement === 'top') {
        container.addClass('flex-col')
        arrow.addClass('rounded-br-sm -bottom-1 left-1/2 -translate-x-1/2')
        container.appendChild(body)
    }
    else if (placement === 'bottom') {
        container.addClass('flex-col')
        arrow.addClass('rounded-tl-sm -top-1 left-1/2 -translate-x-1/2')
        container.appendChild(body)
    }
    else if (placement === 'left') {
        arrow.addClass('rounded-tr-sm -right-1 top-1/2 -translate-y-1/2')
        container.appendChild(body)
    }
    else if (placement === 'right') {
        arrow.addClass('rounded-bl-sm -left-1 top-1/2 -translate-y-1/2')
        container.appendChild(body)
    }

    document.body.appendChild(container)

    let show = () => {
        container.removeClass('hidden')

        setTimeout(() => {
            container.removeClass('opacity-0')
            container.addClass('opacity-80')
            float(el, container).placement(placement).compute()
        }, 5)
    }

    let hide = () => {
        container.removeClass('opacity-80')
        container.addClass('opacity-0')
        setTimeout(() => container.addClass('hidden'), 300)
    }

    el.addEventListener('mouseenter', show)
    el.addEventListener('mouseleave', hide)
}
