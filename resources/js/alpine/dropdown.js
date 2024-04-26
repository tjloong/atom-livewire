// used by Alpine.directive
export default (dropdown, { expression }, { evaluate }) => {
    dropdown.addClass('hidden absolute z-20 opacity-0 transition-opacity duration-300')

    let floater
    let anchor = evaluate(expression)

    dropdown.open = () => {
        dropdown.removeClass('hidden')
        dropdown.style.minWidth = anchor.offsetWidth+'px'

        setTimeout(() => {
            floater = float(anchor, dropdown).autoUpdate().compute()
            dropdown.isOpened = true
            dropdown.removeClass('opacity-0')
            dropdown.addClass('opacity-100')
            dropdown.dispatchEvent(new CustomEvent('dropdown-opened', { bubbles: true }))
        }, 5)
    }

    dropdown.close = () => {
        dropdown.removeClass('opacity-100')
        dropdown.addClass('opacity-0')
    
        setTimeout(() => {
            dropdown.isOpened = false
            dropdown.addClass('hidden')
            dropdown.dispatchEvent(new CustomEvent('dropdown-closed', { bubbles: true }))
            if (floater?.cleanup) floater.cleanup()
        }, 300)
    }

    anchor.addEventListener('click', () => dropdown.open())
}