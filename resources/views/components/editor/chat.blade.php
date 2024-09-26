@php
$field = $attributes->field();
$label = $attributes->get('no-label') ? null : $attributes->get('label');
$transparent = $attributes->get('transparent', false);
$mention = $mention ?? $attributes->get('mention', false);
$content = $attributes->get('content');
$submit = $attributes->submitAction() ?: 'submit';
$placeholder = $attributes->get('placeholder', 'app.label.write-comments');

$upload = [
    'max' => config('atom.max_upload_size'),
    'accept' => '*',
    'multiple' => true,
    ...($attributes->get('upload', [])),
];

$except = ['label', 'transparent', 'content', 'mention', 'placeholder', 'upload'];
@endphp

<x-field :attributes="$attributes->merge([
    'field' => $field,
    'block' => true,
])->only(['field', 'block', 'for', 'no-label', 'label'])">
    <div
        wire:ignore
        x-cloak
        x-data="{
            files: [],
            loading: false,
            uploading: false,
            uploadProgress: null,

            editor () {
                return this.$refs.editor?.editor
            },

            paste (e) {
                let clipboard = e.clipboardData
                let files = Array.from(clipboard.items).filter(item => (item.kind === 'file')).map(item => (item.getAsFile()))
                let text = clipboard.getData('text')

                if (files.length) this.attach(files)
                else if (text) this.editor().chain().focus().insertContent(text).run()
            },

            drop (e) {
                let files = e.dataTransfer.files
                this.attach(files)
            },

            attach (files) {
                if (!files || !files.length) return

                Array.from(files).forEach(file => {
                    file.src = file.type.startsWith('image/') ? URL.createObjectURL(file) : null
                })

                this.files = [...this.files, ...files]

                this.$nextTick(() => this.editor().commands.focus())
            },

            upload () {
                return new Promise((resolve, reject) => {
                    if (!this.files.length) resolve()
                    else {
                        this.uploading = true

                        atom.upload(this.files, {
                            ...{{ Js::from($upload) }},
                            progress: (value) => this.uploadProgress = value,
                        })
                            .then(res => {
                                this.files = []
                                resolve(res.id)
                            })
                            .catch(({ message }) => {
                                $dispatch('alert', { title: tr('app.label.unable-to-upload'), message, type: 'error' })
                                reject()
                            })
                            .finally(() => this.uploading = false)                        
                    }
                })
            },
            
            submit () {
                if (this.loading || this.uploading) return

                let content = this.editor().getHTML()
                content = content.replace(new RegExp('<p></p>$'), '');
                
                if (empty(content) && !this.files.length) return
                
                this.editor().setEditable(false)

                this.upload().then(files => {
                    let timer = setTimeout(() => this.loading = true, 150)

                    this.$wire
                        .call({{ Js::from($submit) }}, { content, files })
                        .then(() => this.editor().chain().setContent('', false).focus().run())
                        .then(() => this.editor().setEditable(true))
                        .then(() => this.$dispatch('chat-submit'))
                        .then(() => clearTimeout(timer))
                        .then(() => this.loading = false)
                })
            },
        }"
        x-on:drop.prevent="drop($event)"
        x-on:paste.stop="paste($event)"
        x-on:editor-enter="submit()"
        {{ $attributes->except($except) }}>
        <div class="{{ pick([
            'editor' => !$transparent,
            'editor editor-transparent' => $transparent,
        ]) }}">
            @if ($mention instanceof \Illuminate\View\ComponentSlot)
                <x-editor.mention
                    :options="$mention->attributes->get('options', [])"
                    :filters="$mention->attributes->get('filters', [])">
                    {{ $mention }}
                </x-editor.mention>
            @elseif (is_string($mention))
                <x-editor.mention :options="$mention"/>
            @elseif ($mention)
                <x-editor.mention :options="get($mention, 'options')" :filters="get($mention, 'filters')"/>
            @endif

            <div
                x-init="Editor({
                    element: $refs.editor,
                    tiptapConfig: {
                        content: {{ Js::from($content) }},
                        placeholder: {{ Js::from(tr($placeholder)) }},
                        autofocus: false,
                        editorProps: {
                            attributes: {
                                class: {{ Js::from(collect([
                                    'editor-content editor-chat-content',
                                    $attributes->get('class'),
                                ])->filter()->join(' ')) }}
                            },
                            // disable pasting and handle using x-on:paste
                            transformPasted () {
                                return ''
                            },
                            // disable drop and handle using x-on:drop
                            handleDrop () {
                                return true
                            },
                        },
                    },
                    disableEnterKey: true,
                    mentionTemplate: $root.querySelector('.editor-mention'),
                })"
                class="editor-container flex">
                <div class="grow">
                    <div x-ref="editor"></div>
                </div>

                <div class="shrink-0 self-end p-2">
                    <div
                        x-show="loading || uploading"
                        class="bg-black rounded-md shadow flex items-center gap-3 py-1.5 px-3">
                        <div class="shrink-0 text-theme">
                            <x-spinner size="14"/>
                        </div>

                        <div class="font-medium text-gray-100 text-sm">
                            <span x-show="loading">{{ tr('app.label.sending') }}</span>
                            <span x-show="uploading">{{ tr('app.label.uploading') }} <span x-text="uploadProgress"></span></span>
                        </div>
                    </div>

                    <div
                        x-show="!loading && !uploading"
                        class="flex items-center divide-x border rounded-md shadow">
                        <x-editor.dropdown>
                            <x-slot:anchor>
                                <button
                                    type="button"
                                    x-ref="anchor"
                                    x-on:click="open()"
                                    x-tooltip.raw="{{ tr('app.label.text-formatting') }}"
                                    class="p-1.5 flex items-center justify-center">
                                    <x-icon bold/>
                                </button>
                            </x-slot:anchor>

                            @foreach ([
                                [
                                    'label' => 'app.label.bold',
                                    'icon' => 'bold',
                                    'command' => 'toggleBold()',
                                ],
                                [
                                    'label' => 'app.label.italic',
                                    'icon' => 'italic',
                                    'command' => 'toggleItalic()',
                                ],
                                [
                                    'label' => 'app.label.underline',
                                    'icon' => 'underline',
                                    'command' => 'toggleBold()',
                                ],
                                [
                                    'label' => 'app.label.strikethrough',
                                    'icon' => 'strikethrough',
                                    'command' => 'toggleStrike()',
                                ],
                                [
                                    'label' => 'app.label.bullet-list',
                                    'icon' => 'unordered-list',
                                    'command' => 'toggleBulletList()',
                                ],
                                [
                                    'label' => 'app.label.ordered-list',
                                    'icon' => 'ordered-list',
                                    'command' => 'toggleOrderedList()',
                                ],
                            ] as $item)
                                <x-editor.dropdown.item
                                    :label="get($item, 'label')"
                                    :icon="get($item, 'icon')"
                                    class="text-sm"
                                    x-on:click="editor().commands.{{ get($item, 'command') }}">
                                </x-editor.dropdown.item>
                            @endforeach            
                        </x-editor.dropdown>

                        <div>
                            <input type="file"
                                x-on:change="attach($event.target.files)"
                                @if (get($upload, 'multiple'))
                                multiple
                                @endif
                                accept="{{ get($upload, 'accept') }}"
                                class="hidden">

                            <button
                                type="button"
                                x-tooltip.raw="{{ tr('app.label.attach') }}"
                                x-on:click="$el.parentNode.querySelector('input').click()"
                                class="p-1.5 flex items-center justify-center">
                                <x-icon attach/>
                            </button>
                        </div>
    
                        <button
                            type="button"
                            x-tooltip.raw="{{ tr('app.label.submit') }}"
                            x-on:click="submit()"
                            class="p-1.5 flex items-center justify-center">
                            <x-icon line-break/>
                        </buttontype=>
                    </div>
                </div>
            </div>

            <template x-if="files.length">
                <div class="py-2 flex items-center gap-3 flex-wrap">
                    <template x-for="(file, i) in files" hidden>
                        <div class="group shrink-0 w-14 flex flex-col gap-2">
                            <figure class="relative w-14 h-14 bg-gray-200 rounded-md overflow-hidden border flex items-center justify-center">
                                <template x-if="file.src">
                                    <img x-bind:src="file.src" class="w-full h-full object-cover">
                                </template>
    
                                <template x-if="!file.src">
                                    <x-icon file/>
                                </template>

                                <template x-if="!loading && !uploading">
                                    <div
                                        x-on:click="files.splice(i, 1)"
                                        class="absolute inset-0 bg-black/50 cursor-pointer items-center justify-center text-white hidden group-hover:flex">
                                        <x-icon delete/>
                                    </div>
                                </template>
                            </figure>

                            <div class="grid text-center">
                                <div x-text="file.name" class="text-xs text-gray-400 truncate"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</x-field>
