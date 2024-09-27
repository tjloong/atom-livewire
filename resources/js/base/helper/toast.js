export default (message, type = null) => {
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

    dispatchEvent(new CustomEvent('toast-received', { bubbles: true, detail }))
}