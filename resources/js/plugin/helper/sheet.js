export default (name, label = null) => {
    let dispatch = (event, data) => {
        dispatchEvent(new CustomEvent(event, { bubbles: true, detail: data }))
    }

    return {
        show: (data, silent = false) => dispatch('sheet-show', { name, label, data, silent }),
        label: (label) => dispatch('sheet-label', { name, label }),
        back: (silent = false) => dispatch('sheet-back', { silent }),
        close: (silent = false) => dispatch('sheet-back', { silent }),
        refresh: () => dispatch('sheet-refresh', { name }),
    }
}
