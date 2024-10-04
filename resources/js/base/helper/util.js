export default {
    highestZIndex: () => {
        let z = []

        for (let dom of document.querySelectorAll('body *:not(script, style)')) {
            let zvalue = window.getComputedStyle(dom, null).getPropertyValue('z-index')
            let display = window.getComputedStyle(dom, null).getPropertyValue('display')

            if (zvalue !== null && zvalue !== 'auto' && zvalue < 999 && display !== 'none') {
                z.push(+zvalue)
            }
        }

        if (!z.length) return 0

        return Math.max(...z)
    },
}