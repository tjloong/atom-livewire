function loadRecaptchaScript (sitekey) {
    let url = 'https://www.google.com/recaptcha/api.js?render='+sitekey

    if (document.querySelector(`script[src="${url}"]`)) return

    let script = document.createElement('script')
    script.src = url
    document.head.appendChild(script)
}

export default (el, { value, expression, modifiers }, { evaluate, evaluateLater }) => {
    if (!value) return console.error('x-recaptcha: Unknown action.');

    let sitekey = document.querySelector('meta[name="application-meta"]').getAttribute('data-recaptcha-sitekey')
    let action = modifiers.filter(val => !['prevent', 'stop'].includes(val))[0]
    let handler = evaluateLater(expression)

    if (sitekey) loadRecaptchaScript(sitekey)

    el.addEventListener(value, (e) => {
        if (modifiers.includes('prevent')) e.preventDefault()
        if (modifiers.includes('stop')) e.stopPropagation()

        if (!window.grecaptcha) handler()
        else {
            el.addClass('is-loading')

            window.grecaptcha.ready(() => {
                window.grecaptcha
                .execute(sitekey, { action: action || value })
                .then(token => {
                    ajax('/__recaptcha').post({ token }).then(valid => {
                        el.removeClass('is-loading')

                        if (valid) handler()
                        else {
                            el.dispatchEvent(new CustomEvent('alert', { bubbles: true, detail: {
                                type: 'error',
                                message: tr('app.alert.recaptcha'),
                            }}))
                        }
                    })
                })
            })
        }
    })
}
