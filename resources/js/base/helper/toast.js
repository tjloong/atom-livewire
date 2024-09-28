export default (message, type = null) => {
    let detail = { type }

    if (typeof message === 'object') detail = { ...detail, ...message }
    else detail = { ...detail, message }

    detail = {
        ...detail,
        title: t(detail.title || '').limit(80),
        message: t(detail.message).limit(100),
    }

    dispatchEvent(new CustomEvent('toast-received', { bubbles: true, detail }))
}