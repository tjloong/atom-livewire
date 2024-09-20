export default class {
    queue
    files
    config

    constructor (files, config) {
        this.files = files
        this.config = config

        if (this.files.some(file => typeof file === 'string')) {
            return this.url()
        }
        else {
            this.queue = config.multiple
                ? Array.from(this.files).map(file => ({ file, completed: false }))
                : [{ file: this.files[0], completed: false }]
    
            return this.upload()
        }
    }

    url () {
        let url = this.files.filter(file => {
            let pattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
            '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator

            return !!pattern.test(file);
        })

        if (!url.length) return new Promise((resolve, reject) => reject(new Error(tr('app.label.invalid-url'))))
        if (!this.config.multiple) url = [url[0]]

        return ajax('/__file/upload').post({ url }).then(files => {
            let id = files.pluck('id')

            return {
                files: this.config.multiple ? [...files] : files[0],
                id: this.config.multiple ? [...id] : id[0],
            }
        })
    }

    upload () {
        let validator = this.validate()
        if (validator.failed) return new Promise((resolve, reject) => reject(new Error(validator.error)))

        let job = this.queue.find(job => (!job.completed))

        if (job) {
            let formdata = new FormData()            
            
            formdata.append('files[]', job.file)
            if (this.config.path) formdata.append('path', this.config.path)
            if (this.config.visibility) formdata.append('visibility', this.config.visibility)

            return ajax('/__file/upload').post(formdata).then(res => {
                job.res = res
                job.completed = true
                this.progress()
                return this.upload()
            })
        }
        else {
            return new Promise((resolve, reject) => {
                let files = this.queue.pluck('res').flat()
                let id = files.pluck('id')

                resolve({
                    files: this.config.multiple ? [...files] : files[0],
                    id: this.config.multiple ? [...id] : id[0],
                })
            })
        }
    }

    progress () {
        if (!this.config.progress) return

        let count = this.queue.length
        let completed = this.queue.filter(job => (job.completed)).length

        this.config.progress(`${completed}/${count}`)
    }

    validate () {
        // scan for unsupported file type
        if (this.config.accept && Array.from(this.files).some(file => {
            const accept = this.config.accept.split(',').map(val => (val.trim())).filter(Boolean)
            return accept.length && accept.some((val) => {
                if (val.endsWith('*')) return !file.type.startsWith(val.replace('*', ''))
                else if (val.startsWith('*')) return !file.type.endsWith(val.replace('*', ''))
                else return !val.includes(file.type)
            })
        })) {
            return { failed: true, error: tr('app.label.unsupported-file-type') }
        }

        // scan for oversize file
        if (Array.from(this.files).some(file => {
            const size = file.size/1024/1024
            return size >= (this.config.max || 10)
        })) {
            return { failed: true, error: tr('app.label.file-oversize', { max: `${this.config.max}MB` }) }
        }

        return { failed: false }
    }
}