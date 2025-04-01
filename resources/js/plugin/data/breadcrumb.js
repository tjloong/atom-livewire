export default () => {
    return {
        sheet: null,
        traces: [],

        init () {
            this.sheet = this.$el.closest('[data-atom-sheet]').getAttribute('data-atom-sheet')
            this.build()
        },

        build () {
            let target = window.sheet.active.findIndexWhere('name', this.sheet)
            this.traces = window.sheet.active.filter((value, index) => (index <= target))
        },
    }
}