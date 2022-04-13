<script>
    document.addEventListener('alpine:init', () => {
        @if (in_array('phone-input', $scripts))
            Alpine.data('phoneInput', (value, code = '+60') => ({
                value,
                code,
                flag: null,
                number: null,

                get countries () {
                    return Array.from(this.$refs.dropdown.querySelectorAll('[data-country-code]'))
                        .map(el => ({ 
                            code: el.getAttribute('data-country-code'), 
                            flag: el.getAttribute('data-country-flag'),
                        }))
                },

                get flag () {
                    if (!this.code) return 
                    return this.countries.find(country => (country.code === this.code)).flag
                },

                init () {
                    if (this.value?.startsWith('+')) {
                        const country = this.countries.find(val => (this.value.startsWith(val.code)))

                        if (country) {
                            this.code = country.code
                            this.number = this.value.replace(country.code, '')
                        }
                    }
                    else this.number = this.value || null
                },
                input () {
                    this.value = this.number ? `${this.code}${this.number}` : null
                    this.close()
                },
                open () {
                    this.$refs.dropdown.classList.remove('hidden')

                    floatPositioning(this.$refs.input, this.$refs.dropdown, {
                        placement: 'bottom',
                        flip: true,
                    })
                },
                close () {
                    this.$refs.dropdown.classList.add('hidden')
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
                        '|', 'bold', 'italic', 'fontSize', 'fontColor', 'link', 'bulletedList', 'numberedList',
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
    })
</script>