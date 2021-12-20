export default (value = null, config = null) => ({
    value,
    sortable: null,

    init () {
        ScriptLoader.load('https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js').then(() => {
            this.setIdentifier()
            this.sortable = new Sortable(this.$el, { ...config, onSort: () => this.input() })
        })
    },

    setIdentifier () {
        if (!this.value) return

        // add identifier to each value
        this.value = this.value.map(val => (val.id || val.uid ? val : { ...val, uid: random() }))

        // map the item elements to value
        Array.from(this.$el.children)
            .forEach((child, i) => child.setAttribute('data-id', this.value[i].id || this.value[i].uid))
    },

    input () {
        const sorted = []

        Array.from(this.$el.children).forEach(child => {
            const id = child.getAttribute('data-id')
            const value = this.value.find(val => (val.id.toString() === id))
            sorted.push(value)
        })

        this.$dispatch('input', sorted)
    },
})