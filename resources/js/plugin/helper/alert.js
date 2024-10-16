export default (message, type = null) => {
    let detail = { type }

    if (typeof message === 'object') detail = { ...detail, ...message }
    else detail = { ...detail, message }

    detail = {
        ...detail,
        title: detail.title ? t(detail.title) : null,
        message: t(detail.message),
    }

    dispatchEvent(new CustomEvent('alert', { bubbles: true, detail }))
}