let dispatch = (message, type = null) => {
    let detail = { type }

    if (typeof message === 'string') detail.message = tr(message).limit(100)
    else {
        detail = {
            ...detail,
            ...message,
            title: tr(message.title).limit(80),
            message: tr(message.message).limit(100),
        }
    }

    document.dispatchEvent(new CustomEvent('toast-received', { bubbles: true, detail }))
}

export default {
    info: (message) => dispatch(message, 'info'),
    error: (message) => dispatch(message, 'error'),
    success: (message) => dispatch(message, 'success'),
    warning: (message) => dispatch(message, 'warning'),
    make: (message) => dispatch(message),
}