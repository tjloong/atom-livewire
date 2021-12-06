class ScriptLoader
{
    constructor () {
        this.scripts = []
    }

    isCompleted () {
        const completed = this.scripts.filter(script => (script.status === 'completed'))
        return this.scripts.length === completed.length
    }

    pushScripts (scripts) {
        scripts = [scripts].flat().map(script => {
            if (typeof script === 'string') return { src: script, type: 'js' }
            else return script
        })

        scripts.forEach(script => {
            if (!this.scripts.some(val => (val.src === script.src))) {
                this.scripts.push({ ...script, status: 'pending' })
            }
        })
    }

    fetch () {
        // js
        this.scripts.filter(script => (script.type === 'js' && script.status === 'pending')).forEach(script => {
            const s = document.createElement('script')
            s.type = 'text/javascript'
            s.src = script.src

            s.addEventListener('load', () => script.status = 'completed')

            document.head.appendChild(s)
        })

        // css
        this.scripts.filter(script => (script.type === 'css' && script.status === 'pending')).forEach(script => {
            const s = document.createElement('link')
            s.href = script.src
            s.type = 'text/css'
            s.rel = 'stylesheet'

            document.head.appendChild(s)

            script.status = 'completed'
        })
    }

    load (scripts) {
        this.pushScripts(scripts)
        this.fetch()

        let timer

        return new Promise((resolve, reject) => {
            if (this.isCompleted()) resolve()
            else {
                timer = setInterval(() => {
                    if (this.isCompleted()) {
                        clearInterval(timer)
                        resolve()
                    }
                }, 100)
            }
        })
    }
}

window.ScriptLoader = new ScriptLoader()