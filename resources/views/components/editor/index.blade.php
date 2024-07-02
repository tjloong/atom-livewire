@php
$field = $attributes->field();
$mode = $attributes->get('mode', 'html');
$label = $attributes->get('no-label') ? null : $attributes->get('label');
$readonly = $attributes->get('readonly', false);
$transparent = $attributes->get('transparent', false);
$lazy = $attributes->modifier('lazy');
$placeholder = $attributes->get('placeholder', 'Write something...');
$except = ['label', 'enabled', 'readonly', 'placeholder', 'class', 'wire:model', 'wire:model.defer', 'wire:model.lazy'];
@endphp

<x-field :attributes="$attributes->merge([
    'field' => $field,
    'block' => true,
])->only(['field', 'block', 'for', 'no-label', 'label'])">
    @if ($readonly)
        <div
            x-data="{ content: @entangle($attributes->wire('model')) }"
            x-html="content"
            {{ $attributes->class(['editor-content'])->only('class') }}>
        </div>
    @else
        <div
            wire:ignore
            x-cloak
            x-data="{
                content: @entangle($attributes->wire('model')),
                mode: @js($mode),
                lazy: @js($lazy),
                active: @js($transparent ? false : true),
                transparent: @js($transparent),
                placeholder: @js(tr($placeholder)),

                init () {
                    this.$watch('content', content => {
                        if (!this.editor()) return
                        if (content !== this.getLatestContent()) {
                            this.commands().setContent(this.getLatestContent(), false)
                        }
                    })
                },

                enable (bool) {
                    if (!this.transparent) return
                    this.active = bool
                },

                sync () {
                    if (this.editor().isEmpty) this.content = null
                    else this.content = this.getLatestContent()
                },

                editor () {
                    return this.$refs.editor?.editor
                },

                can () {
                    return this.editor().can()
                },

                commands () {
                    this.editor().chain().focus()
                    return this.editor().commands
                },

                getEditorConfigs () {
                    return {
                        content: this.content,
                        placeholder: this.placeholder,
                        autofocus: this.transparent ? 'end' : false,
                        editorProps: {
                            attributes: {
                                class: 'editor-content mx-3 focus:outline-none {{ $attributes->get('class') }}',
                            },
                        },
                    }
                },

                getLatestContent () {
                    let content = {
                        html: this.editor().getHTML(),
                        json: this.editor().getJSON(),
                        text: this.editor().getText(),
                    }

                    return content[this.mode]
                },

                isEmpty () {
                    if (typeof this.content === 'string') return empty(this.content.striptags())
                    else return empty(this.content)
                },
            }"
            x-modelable="content"
            {{ $attributes->except($except) }}>
            <template x-if="active">
                <div
                    x-init="Editor($refs.editor, getEditorConfigs())"
                    x-on:editor-update="!lazy && sync()"
                    x-on:editor-blur="lazy && sync()"
                    x-on:click.away="sync(); enable(false)"
                    class="editor relative bg-white border border-gray-300 rounded-md hover:ring-1 hover:ring-gray-300">
                    <template x-if="mode !== 'text'">
                        <div class="sticky -top-5 z-30 flex items-center flex-wrap border-b bg-white m-1">
                            <x-editor.heading/>
                            <x-editor.text/>
                            <x-editor.bullet/>
                            <x-editor.tools/>
                            <x-editor.table/>
                            <x-editor.media/>
                            <x-editor.actions/>
                        </div>
                    </template>

                    <div x-ref="editor"></div>
                </div>
            </template>

            <template x-if="!active">
                <div 
                    x-on:click.stop="enable(true)"
                    class="transition-all cursor-pointer rounded-lg hover:border-2 hover:border-dashed hover:px-2 hover:bg-white">
                    <template x-if="!isEmpty() && mode === 'html'">
                        <div x-html="content" class="editor-content"></div>
                    </template>

                    <template x-if="!isEmpty() && mode === 'text'">
                        <div x-text="content" {{ $attributes->only('class') }}></div>
                    </template>

                    <template x-if="isEmpty()">
                        <input type="text" x-bind:placeholder="placeholder" class="appearance-none w-full" readonly>
                    </template>
                </div>
            </template>
        </div>
    @endif
</x-field>