<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formPhone', (config) => ({
            show: false,
            value: null,
            tel: null,
            search: null,
            countries: [],
            code: config.code,
            focus: config.focus,
        
            get flag () {
                return this.countries.find(val => (val.code === this.code)).src
            },
        
            get results () {
                if (!this.search) return null
        
                return this.countries
                    .filter(cn => (cn.name.toLowerCase().includes(this.search)))
                    .map(cn => (cn.code))
            },
        
            init () {
                if (this.focus) this.$nextTick(() => this.$refs.tel.focus())
                
                this.countries = Array.from(this.$refs.options.querySelectorAll('a')).map(elem => ({
                    'src': elem.querySelector('img')?.getAttribute('src'),
                    'name': elem.getAttribute('data-name'),
                    'code': elem.getAttribute('data-code'),
                }))
        
                this.value = config.model ? this.$wire.get(config.model) : (config.value || null)
        
                if (this.value?.startsWith('+')) {
                    const country = this.countries.find(cn => (this.value.startsWith(cn.code)))
        
                    if (country) {
                        this.code = country.code
                        this.tel = this.value.replace(this.code, '')
                    }
                    else this.tel = this.value.replace('+', '')
                }
                else this.tel = this.value || null
            },
        
            pattern () {
                this.tel = this.tel.replace(/\D/g, '')
                this.input()
            },
        
            select (code) {
                this.code = code
                this.input()
            },
        
            input () {
                this.value = this.tel ? `${this.code}${this.tel}` : null
                this.$refs.input.dispatchEvent(new Event('input', { bubble: true }))
                this.show = false
                this.search = null
            },
        }))
    })
</script>