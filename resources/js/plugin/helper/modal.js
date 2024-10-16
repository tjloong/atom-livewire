export default (name = 'modal') => {
    let dispatch = (event, data) => {
        dispatchEvent(new CustomEvent(event, { bubbles: true, detail: data }))
    }

    return {
        show: (data = null) => dispatch('modal-show', { name, data }),
        close: () => dispatch('modal-close', { name }),
    }
}
