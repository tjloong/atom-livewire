export default (name, label = null) => {
    let dispatch = (event, data) => {
        dispatchEvent(new CustomEvent(event, { bubbles: true, detail: data }))
    }

    return {
        show: (data) => dispatch('sheet-show', { name, label, data }),
        label: (label) => dispatch('sheet-label', { name, label }),
        back: () => dispatch('sheet-back'),
        close: () => dispatch('sheet-back'),
        refresh: () => dispatch('sheet-refresh', { name }),
    }
}
