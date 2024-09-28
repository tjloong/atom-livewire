export default (message, type = null) => {
    let detail = { type }

    if (typeof message === 'object') detail = { ...detail, ...message }
    else detail = { ...detail, message: message.toString() }

    return new Promise((resolve, reject) => {
        dispatchEvent(new CustomEvent('confirm', { bubbles: true, detail: {
            ...detail,
            onAccept: () => resolve(),
            onCancel: () => reject(),
        }}))
    })
}