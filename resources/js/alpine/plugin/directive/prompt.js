export default (el, { modifiers, expression }, { evaluateLater, evaluate }) => {
    el.addEventListener('click', e => {
        e.stopPropagation()

        let params = evaluate(expression)

        let type = 'info'
        if (modifiers.includes('warning')) type = 'warning'
        if (modifiers.includes('error')) type = 'error'
        if (modifiers.includes('success')) type = 'success'

        if (modifiers.includes('alert')) {
            el.dispatchEvent(new CustomEvent('alert', { bubbles: true, detail: {
                type,
                title: tr(params.title || 'Alert'),
                message: tr(params.message),
            }}))
        }
        else if (modifiers.includes('confirm')) {
            el.dispatchEvent(new CustomEvent('confirm', { bubbles: true, detail: {
                type,
                title: tr(params.title || (modifiers.includes('submit') ? 'app.alert.submit.title' : 'Please Confirm')),
                message: tr(params.message || (modifiers.includes('submit') ? 'app.alert.submit.message' : 'Are you sure to proceed?')),
                onConfirmed: params.confirm,
            }}))
        }
        else if (modifiers.includes('trash')) {
            el.dispatchEvent(new CustomEvent('confirm', { bubbles: true, detail: {
                title: tr(params.title || 'app.alert.trash.title'),
                message: tr(params.message || 'app.alert.trash.message', params.count),
                type: 'error',
                onConfirmed: params.confirm,
            } }))
        }
        else if (modifiers.includes('delete')) {
            el.dispatchEvent(new CustomEvent('confirm', { bubbles: true, detail: {
                title: tr(params.title || 'app.alert.delete.title'),
                message: tr(params.message || 'app.alert.delete.message'),
                type: 'error',
                onConfirmed: params.confirm,
            } }))
        }
    })
}