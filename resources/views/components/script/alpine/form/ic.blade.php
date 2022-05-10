<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formIc', (config) => ({
            value: null,
            focusElem: [],
        
            segments: {
                head: null,
                body: null,
                tail: null,
            },
        
            init () {
                if (config.focus) this.$nextTick(() => this.$refs.head.focus())
        
                if (config.model) {
                    const val = this.$wire.get(config.model)
                    if (!val) return
                    
                    const splits = val.split('-')
        
                    if (splits.length > 1) {
                        this.segments.head = splits[0]
                        this.segments.body = splits[1] || null
                        this.segments.tail = splits[2] || null
                    }
                    else if (+val) {
                        this.segments.head = val.substring(0, 6)
                        this.segments.body = val.substring(6, 8)
                        this.segments.tail = val.substring(8)
                    }
        
                    this.input()
                }
                else this.value = config.value || null
            },
        
            focus (e) {
                this.focusElem.push(e.target)
            },
        
            blur (e) {
                this.focusElem = this.focusElem.filter(elem => elem !== e.target)
            },
        
            pattern (segment) {
                if (this.segments[segment]) this.segments[segment] = this.segments[segment].replace(/\D/g, '')
        
                const elem = this.$refs[segment]
                const max = elem.getAttribute('maxlength')
                const val = this.segments[segment]
        
                if (val.length >= parseInt(max)) {
                    if (segment === 'head') this.$refs.body.select()
                    if (segment === 'body') this.$refs.tail.select()
                }
                else if (!val.length) {
                    if (segment === 'tail') this.$refs.body.select()
                    if (segment === 'body') this.$refs.head.select()
                }
        
                this.input()
            },
        
            input () {
                this.value = [this.segments.head, this.segments.body, this.segments.tail].filter(Boolean).join('-')
                this.$nextTick(() => {
                    this.$refs.input.dispatchEvent(new Event('input', { bubble: true }))
                })
            },
        }))
    })
</script>
