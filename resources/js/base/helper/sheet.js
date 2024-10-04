export default (name, label = null) => {
    let dispatch = (event, data) => {
        dispatchEvent(new CustomEvent(event, { bubbles: true, detail: data }))
    }

    return {
        show: (data) => dispatch('sheet-show', { name, label, data }),
        back: () => dispatch('sheet-back'),
    }
}
