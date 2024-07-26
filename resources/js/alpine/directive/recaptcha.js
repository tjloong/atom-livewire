function getRecaptchaToken (sitekey, action) {
    return new Promise((resolve, reject) => {
        window.grecaptcha.ready(() => {
            window.grecaptcha
                .execute(sitekey, { action: action || 'submit' })
                .then(token => resolve(token))
        })
    })
}

function handleRecaptchaToken(token, expression, evaluate, evaluateLater) {
    let handler = evaluateLater(expression)
    let setToken = generateSetTokenHander(evaluateLater)
    setToken(token)
    handler()
}

function generateSetTokenHander(evaluateLater) {
    let handler = evaluateLater(`($token) => $wire.set('form.recaptcha_token', $token)`)

    return (token) => {
        // In the case of `x-wire-on:{event}="handleEvent"`, let us call it manually...
        Alpine.dontAutoEvaluateFunctions(() => {
            handler(
                // If a function is returned, call it with the args params...
                received => {
                    if (typeof received === 'function') received(token)
                },
                // Provide $args to the scope in case they want to call their own function...
                { scope: {
                    $token: token,
                } },
            )
        })
    }
}

export default (el, { value, expression, modifiers }, { evaluate, evaluateLater }) => {
    if (!value) return console.error('x-recaptcha: Unknown action.');

    let sitekey = document.querySelector('meta[name="application-meta"]').getAttribute('data-recaptcha-sitekey')
    let action = modifiers.filter(val => !['prevent', 'stop'].includes(val))[0]
    let recaptchaHandler = () => getRecaptchaToken(sitekey, action)
        .then(token => handleRecaptchaToken(token, expression, evaluate, evaluateLater))

    el.addEventListener(value, (e) => {
        if (modifiers.includes('prevent')) e.preventDefault()
        if (modifiers.includes('stop')) e.stopPropagation()

        if (sitekey && !window.grecaptcha) {
            let script = document.createElement('script')
            script.src = 'https://www.google.com/recaptcha/api.js?render='+sitekey
            script.onload = () => recaptchaHandler()
            document.head.appendChild(script)
        }
        else if (sitekey && window.grecaptcha) {
            recaptchaHandler()
        }
    })
}
