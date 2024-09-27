export default (name = 'modal') => {
    let dispatch = (event, data) => {
        dispatchEvent(new CustomEvent(event, { bubbles: true, detail: data }))
    }

    return {
        show: () => dispatch('modal-show', { name }),
        close: () => dispatch('modal-close', { name }),
    }
}
