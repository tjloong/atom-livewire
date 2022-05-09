<script>
    document.addEventListener('alpine:init', () => {
        @if (in_array('ic-input', $scripts))
            Alpine.data('icInput', (config) => ({
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
        @endif

        @if (in_array('seo-input', $scripts))
            Alpine.data('seoInput', (config) => ({
                value: {
                    title: null,
                    description: null,
                    image: null,
                },

                init () {
                    if (config.model) this.value = { ...this.value, ...this.$wire.get(config.model) }
                    else if (config.value) this.value = { ...this.value, ...config.value }
                },

                input () {
                    this.$nextTick(() => this.$dispatch('seo-updated', this.value))
                }
            }))
        @endif

        @if (in_array('date-input', $scripts))
            Alpine.data('dateInput', (config) => ({
                fp: null,
                show: false,
                value: null,
                loading: false,
                settings: config.settings,

                init () {
                    if (config.model) this.value = this.$wire.get(config.model) || null
                    else if (config.value) this.value = config.value || null
                },
    
                open () {
                    if (!window.flatpickr) this.loading = true
    
                    ScriptLoader.load([
                        { src: 'https://cdn.jsdelivr.net/npm/flatpickr', type: 'js' },
                        { src: 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', type: 'css' },
                    ]).then(() => {
                        this.loading = false
                        this.show = true

                        this.fp = flatpickr(this.$refs.datepicker, {
                            inline: true,
                            dateFormat: 'Y-m-d',
                            defaultDate: this.value,
                            onClose: () => this.close(),
                            onChange: (selectedDate, dateStr) => this.value = dateStr,
                            ...this.settings,
                        })
                    })
                },

                close () { 
                    this.show = false
                },

                clear () {
                    this.value = null
                    this.$dispatch('input', '')
                },
            }))
        @endif

        @if (in_array('phone-input', $scripts))
            Alpine.data('phoneInput', (config) => ({
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
        @endif

        @if (in_array('amount-input', $scripts))
            Alpine.data('amountInput', (value) => ({
                value,

                stringToNumber (val) {
                    if (Number.isFinite(val)) return val

                    val = val.replace(/[^\d\.]+/g, '')
                    val = val.replace(/(\..*)\./g, '$1')
                    val = parseFloat(val)
                    val = !val || !Number.isFinite(val) ? null : val

                    return val || 0
                },

                updateValue (val) {
                    this.value = this.stringToNumber(val)
                    this.$nextTick(() => this.$refs.input.dispatchEvent(new Event('input', { bubbles: true })))
                },
            }))
        @endif

        @if (in_array('title-input', $scripts))
            Alpine.data('titleInput', (config) => ({
                value: null,

                init () {
                    if (config.model) this.value = this.$wire.get(config.model)
                    else if (config.value) this.value = config.value
                },
            }))
        @endif

        @if (in_array('tags-input', $scripts))
            Alpine.data('tagsInput', (value, options) => ({
                value,
                options: [],

                init () {
                    this.options = options.map(opt => ({ ...opt, selected: this.value.includes(opt.value) }))
                },

                open () {
                    this.$refs.dropdown.classList.remove('hidden')
                    this.$refs.dropdown.classList.add('opacity-0')

                    floatPositioning(this.$refs.input, this.$refs.dropdown, {
                        placement: 'bottom',
                        flip: true,
                    })

                    this.$refs.dropdown.classList.remove('opacity-0')
                },

                close () {
                    this.$refs.dropdown.classList.add('hidden')
                },
                
                toggle (val) {
                    this.options = this.options.map(opt => {
                        if (opt.value === val.value) opt.selected = !opt.selected
                        return opt
                    })

                    this.$dispatch('input', this.options
                        .filter(opt => (opt.selected))
                        .map(opt => (opt.value))
                    )

                    this.close()
                },
            }))
        @endif

        @if (in_array('richtext-input', $scripts))
            Alpine.data('richtextInput', (config) => ({
                uid: config.uid,
                file: false,
                value: null,
                loading: false,
                toolbar: config.toolbar,
                placeholder: config.placeholder,

                init () {
                    this.loading = true

                    if (config.model) this.value = this.$wire.get(config.model)
                    else if (config.value) this.value = config.value

                    ScriptLoader.load('/js/ckeditor5/ckeditor.js')
                        .then(() => this.createEditor())
                        .finally(() => this.loading = false)
                },

                createEditor () {
                    ClassicEditor
                        .create(this.$refs.ckeditor, { placeholder: this.placeholder, toolbar: this.toolbar })
                        .then(editor => {
                            // initial content
                            if (this.value) editor.setData(this.value)

                            // onchange update
                            editor.model.document.on('change:data', () => {
                                this.value = editor.getData()
                                this.$nextTick(() => this.$refs.input.dispatchEvent(new Event('input', { bubble: true })))
                            })
                            
                            // insert media
                            editor.ui.view.toolbar.on('insert-media:click', () => {
                                this.$dispatch(`${this.uid}-uploader-open`)

                                const insert = (event) => {
                                    const files = event.detail

                                    files.forEach(file => {
                                        if (file.is_image) {
                                            editor.model.change(writer => {
                                                const imageElement = writer.createElement('imageBlock', { src: file.url })
                                                editor.model.insertContent(imageElement, editor.model.document.selection);
                                            })
                                        }
                                        else if (file.is_video) {
                                            const html = `<video controls class="w-full min-h-[300px]"><source src="${file.url}" type="${file.mime}"></video>`
                                            const viewFragment = editor.data.processor.toView(html)
                                            const modelFragment = editor.data.toModel(viewFragment)

                                            editor.model.insertContent(modelFragment)
                                        }
                                        else if (file.is_audio) {
                                            const html = `<audio controls class="w-full"><source src="${file.url}" type="${file.mime}"></audio>`
                                            const viewFragment = editor.data.processor.toView(html)
                                            const modelFragment = editor.data.toModel(viewFragment)

                                            editor.model.insertContent(modelFragment)
                                        }
                                        else if (file.type === 'youtube') {
                                            editor.execute('mediaEmbed', file.url)
                                        }
                                    })

                                    window.removeEventListener(`${this.uid}-uploader-completed`, insert)
                                }

                                window.addEventListener(`${this.uid}-uploader-completed`, insert)
                            })
                        })
                },
            }))
        @endif

        @if (in_array('image-input', $scripts))
            Alpine.data('imageInput', (config) => ({
                value: null,
                shape: config.shape,
                placeholder: config.placeholder,

                init () {
                    if (config.model) this.value = this.$wire.get(config.model)
                    else if (config.value) this.value = config.value
                },

                select (file) {
                    this.value = file.id
                    this.placeholder = file.url
                    
                    this.$nextTick(() => this.input())
                },

                clear () {
                    this.value = null
                    this.placeholder = null

                    this.$nextTick(() => this.input())
                },

                input () {
                    this.$refs.input.dispatchEvent(new Event('input', { bubble: true }))
                },
            }))
        @endif

        @if (in_array('picker-input', $scripts))
            Alpine.data('pickerInput', (getter, labelKey) => ({
                labelKey,
                show: false,
                text: null,
                loading: true,
                options: [],
                paginator: null,
    
                fetch (page = 1) {
                    this.loading = true
    
                    this.$wire[getter](page, this.text).then(res => {
                        this.paginator = res
                        this.options = page === 1 ? res.data : this.options.concat(res.data)
                        this.loading = false
                    })
                },
    
                pick (opt) {
                    this.selected = opt
                    this.$dispatch('input', opt.id)
                    this.close()
                },
    
                open () {
                    document.documentElement.classList.add('overflow-hidden')
                    this.options = []
                    this.text = null
                    this.show = true
                    this.fetch()
                },
                
                close () {
                    document.documentElement.classList.remove('overflow-hidden')
                    this.show = false
                },
            }))
        @endif

        @if (in_array('sortable-input', $scripts))
            Alpine.data('sortableInput', (value = null, config = null) => ({
                value,
                sortable: null,

                get children () {
                    return Array.from(this.$el.children)
                        .filter(child => (!child.tagName.includes('TEMPLATE')))
                },

                init () {
                    ScriptLoader.load('https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js').then(() => {
                        this.setIdentifier()
                        this.sortable = new Sortable(this.$el, { ...config, onSort: () => this.input() })
                    })
                },

                setIdentifier () {
                    if (!this.value) return

                    // add identifier to each value
                    this.value = this.value.map(val => ({ ...val, sortableId: random() }))

                    // map the item elements to value
                    this.children.forEach((child, i) => child.setAttribute('data-sortable-id', this.value[i].sortableId))
                },

                input () {
                    if (!this.value) this.$dispatch('input')
                    else {
                        const sorted = []

                        this.children.forEach(child => {
                            const id = child.getAttribute('data-sortable-id')
                            const value = this.value.find(val => (val.sortableId === id))
                            sorted.push(value)
                        })

                        this.$dispatch('input', sorted)
                    }
                },
            }))
        @endif

        @if (in_array('sidenav', $scripts))
            Alpine.data('sidenav', (config) => ({
                show: false,
                value: null,

                init () {
                    this.setValue()
                    Livewire.hook('message.received', (message, component) => this.setValue())
                },

                setValue () {
                    if (config.model) this.value = this.$wire.get(config.model)
                    else if (config.value) this.value = config.value
                },

                select (val) {
                    this.show = !this.show
                    this.value = val
                    this.$nextTick(() => this.$dispatch('input', this.value))
                }
            }))
        @endif

        @if (in_array('table', $scripts))
            Alpine.data('tableHead', (key) => ({
                get sorted () {
                    return this.$wire.get('sortBy') === key
                },

                sort () {
                    if (this.$wire.get('sortBy') === key) {
                        this.$wire.set('sortOrder', this.$wire.get('sortOrder') === 'asc' ? 'desc' : 'asc')
                    }
                    else this.$wire.set('sortOrder', 'asc')

                    this.$wire.set('sortBy', key)
                },
            }))
        @endif
    })
</script>