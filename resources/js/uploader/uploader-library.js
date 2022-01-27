export default () => ({
    files: [],
    search: null,
    loading: false,
    paginator: null,
    fetch (page = 1) {
        this.loading = true
        this.$wire.getFiles(page, this.search).then(res => {
            this.paginator = res
            this.files = page === 1 ? res.data : this.files.concat(res.data)
        }).finally(() => this.loading = false)
    },
    getSelected () {
        return this.files.filter(file => (file.selected)).map(file => (file.id))
    },
    submit () {
        this.$wire.selectFiles(this.getSelected())
        this.files.map(file => (file.selected = false))
    },
    init () {
        this.fetch()
        this.$watch('search', search => this.fetch(1))
    },
})