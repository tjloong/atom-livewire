function generateLivewireEventHandler(expression, evaluateLater) {
    let handle = evaluateLater(expression)

    return (args) => {
        // In the case of `x-wire-on:{event}="handleEvent"`, let us call it manually...
        Alpine.dontAutoEvaluateFunctions(() => {
            handle(
                // If a function is returned, call it with the args params...
                received => {
                    if (typeof received === 'function') received(args)
                },
                // Provide $args to the scope in case they want to call their own function...
                { scope: {
                    $args: args,
                } },
            )
        })
    }
}

export default (el, { value, expression }, { evaluateLater }) => {
    let eventName = value.camel()
    let handler = generateLivewireEventHandler(expression, evaluateLater)
    Livewire.on(eventName, (args) => handler(args))
}