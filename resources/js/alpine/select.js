// for <x-form.select/>
// used by Alpine.data
export default (config) => ({
    ...config,
    focus: false,
    loading: false,
    search: null,
    pointer: null,

    get selection () {
        return this.multiple
            ? this.options.filter(opt => (this.value.includes(opt.value)))
            : this.options.find(opt => (opt.value === this.value))
    },

    get isSearchable () {
        return this.searchable && (
            this.loading
            || (!this.loading && (this.options.length || !empty(this.search)))
        )
    },

    get isEmpty () {
        return this.multiple
            ? (!this.value || !this.value.length)
            : (this.value === null || this.value === undefined)
    },

    get filtered () {
        return this.options.filter(opt => (!opt.hidden))
    },

    init () {
        this.$nextTick(() => {
            if (this.value === undefined && this.multiple) this.value = []
            if (!this.isEmpty) this.filter()
        })

        this.$watch('search', (search) => this.filter(search))
    },

    opened () {
        this.$refs.search?.focus()
        if (!this.options.length && this.callback) this.filter()
    },

    close () {
        this.search = null
        this.$refs.dropdown.close()
    },

    select (index) {
        const opt = this.filtered[index]

        if (this.multiple) {
            const index = this.value.indexOf(opt.value)
            if (index === -1) this.value.push(opt.value)
            else this.value.splice(index, 1)
        }
        else if (this.value === opt.value) this.value = null
        else this.value = opt.value

        this.$dispatch('input', this.value)

        if (!this.multiple) this.close()
    },

    remove (opt = null) {
        if (opt === null) {
            if (this.multiple) this.value = []
            else this.value = null
        }
        else {
            const index = this.value.indexOf(opt.value)
            this.value.splice(index, 1)
        }

        this.$dispatch('input', this.value)
    },

    fetch (callback, params) {
        this.loading = true

        ajax(this.endpoint)
        .post({ callback, params, value: this.value })
        .then(res => this.options = res)
        .finally(() => {
            this.loading = false
            this.$refs.search?.focus()
        })
    },

    filter (search) {
        if (this.callback) {
            this.fetch(this.callback, { ...this.params, search })
        }
        else {
            this.options = this.options.map(opt => ({
                ...opt,
                hidden: !empty(search) && (
                    !opt.label.toLowerCase().includes(search.toLowerCase())
                    && !opt.small?.toLowerCase().includes(search.toLowerCase())
                    && !opt.caption?.toLowerCase().includes(search.toLowerCase())
                ),
            }))
        }

        this.$dispatch('fetch', search)
    },

    navigate (e) {
        const isUp = e.key === 'ArrowUp'
        const isDown = e.key === 'ArrowDown'
        const max = this.filtered.length ? this.filtered.length - 1 : 0

        if (isDown && !this.$refs.dropdown.isOpened) this.$refs.dropdown.open()
        else {
            if (this.pointer === null) this.pointer = 0
            else if (isDown) this.pointer++
            else if (isUp) this.pointer--

            if (this.pointer < 0) this.pointer = 0
            if (this.pointer > max) this.pointer = max
        }
    },

    isSelected (opt) {
        return this.multiple && this.value && this.value.includes(opt.value)
            || !this.multiple && this.value === opt.value
    },
})
