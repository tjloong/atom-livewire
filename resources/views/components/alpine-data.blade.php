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

        @if (in_array('date-input', $scripts))
            Alpine.data('dateInput', (config) => ({
                fp: null,
                show: false,
                value: null,
                loading: false,
                settings: config.settings,

                init () {
                    if (config.model) this.value = this.$wire.get(config.model)
                    else if (config.value) this.value = config.value
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
                    this.$dispatch('input', null)
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

        @if (in_array('richtext-input', $scripts))
            Alpine.data('richtextInput', (toolbar, placeholder) => ({
                uid: null,
                file: false,
                loading: false,

                init () {
                    this.loading = true
                    ScriptLoader.load('/js/ckeditor5/ckeditor.js')
                        .then(() => this.createEditor())
                        .finally(() => this.loading = false)
                },

                createEditor () {
                    const defaultToolbar = [
                        'heading',
                        '|', 'bold', 'italic', 'underline', 'fontSize', 'fontColor', 'link', 'bulletedList', 'numberedList',
                        '|', 'alignment', 'outdent', 'indent', 'horizontalLine',
                        '|', 'blockQuote', 'insertMedia', 'insertTable', 'undo', 'redo',
                        '|', 'sourceEditing',
                    ]

                    ClassicEditor.create(this.$refs.ckeditor, {
                        placeholder: placeholder || 'Content goes here',
                        toolbar: toolbar || defaultToolbar,
                    }).then(editor => {
                        // initial content
                        if (this.value) editor.setData(this.value)

                        // onchange update
                        editor.model.document.on('change:data', () => {
                            this.$refs.input.value = editor.getData()
                            this.$refs.input.dispatchEvent(new Event('change'))
                        })
                            
                        // insert media
                        editor.ui.view.toolbar.on('insert-media:click', () => {
                            this.$dispatch(`richtext-uploader-open`)

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

                                window.removeEventListener(`richtext-uploader-completed`, insert)
                            }

                            window.addEventListener(`richtext-uploader-completed`, insert)
                        })
                    })
                },
            }))
        @endif

        @if (in_array('image-input', $scripts))
            Alpine.data('imageInput', () => ({
                select (file) {
                    this.value = file.id
                    this.placeholder = file.url
                    this.$nextTick(() => this.input())
                },

                clear () {
                    this.value = this.placeholder = null
                    this.$nextTick(() => this.input())
                },

                input () {
                    this.$refs.input.dispatchEvent(new Event('change'))
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

        @if (in_array('table', $scripts))
            Alpine.data('sortableTableHead', (key) => ({
                isSorted () { 
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