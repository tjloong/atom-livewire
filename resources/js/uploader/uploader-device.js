export default (types, max = 10) => ({
    max,
    types,
    status: null,
    progress: 0,
    complete (status) {
        this.status = status
        this.$refs.input.value = ''
    },
    validate (files) {
        if (this.status === 'unsupported') {
            this.status = null
            return
        }

        const sum = Array.from(files).reduce((acc, file) => (file.size + acc), 0)
        const size = Math.round(sum/1024/1024, 2)

        if (size >= this.max) this.status = 'oversize'
        else this.upload(files)
    },
    upload (files) {
        this.status = 'uploading'

        const finishCallback = () => this.complete('completed')
        const failedCallback = () => this.complete('failed')
        const progressCallback = (event) => this.progress = event.detail.progress

        this.$wire.uploadMultiple('uploadedFiles', files, finishCallback, failedCallback, progressCallback)
    },
    scan (e) {
        this.status = 'scanning'

        const items = Array.from(e.dataTransfer.items).filter(item => item.kind === 'file')
        const unsupported = items.some(item => (!this.types.includes(item.type)))

        if (unsupported) this.status = 'unsupported'
    },
})