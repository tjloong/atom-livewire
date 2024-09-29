class Confirmer {
    make (message, type) {
        this.detail = { type }

        if (typeof message === 'object') this.detail = { ...this.detail, ...message }
        else this.detail = { ...this.detail, message: message.toString() }

        this.promise = new Promise((resolve, reject) => {
            dispatchEvent(new CustomEvent('confirm', { bubbles: true, detail: {
                ...this.detail,
                onAccept: () => resolve(true),
                onCancel: () => resolve(false),
            }}))  
        })

        return this
    }

    // accept callback
    then (fn) {
        this.promise.then(accepted => {
            if (accepted) fn()
            return accepted
        })

        return this
    }

    // cancel callback
    catch (fn) {
        this.promise.then(accepted => {
            if (!accepted) fn()
            return accepted
        })

        return this
    }
}

export default (message, type = null) => {
    let confirmer = new Confirmer()
    return confirmer.make(message, type)
}
