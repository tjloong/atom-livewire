let dispatch = (message, type = null) => {
    document.dispatchEvent(new CustomEvent('toast-received', { bubbles: true, detail: { message, type } }))
}

export default {
    info: (message) => dispatch(message, 'info'),
    error: (message) => dispatch(message, 'error'),
    success: (message) => dispatch(message, 'success'),
    warning: (message) => dispatch(message, 'warning'),
    make: (message) => dispatch(message),
}