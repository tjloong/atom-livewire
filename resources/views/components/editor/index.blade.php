@php
$field = $attributes->field();
$mode = $attributes->get('mode', 'html');
$label = $attributes->get('no-label') ? null : $attributes->get('label');
$readonly = $attributes->get('readonly', false);
$autofocus = $attributes->get('autofocus', false);
$transparent = $attributes->get('transparent', false);
$suggestion = $attributes->get('suggestion', false);
$lazy = $attributes->modifier('lazy');
$placeholder = $attributes->get('placeholder', 'Write something...');

$buttons = $attributes->get('no-buttons') ? false : (
    collect($attributes->get('buttons') ?? [
        'heading',
        'text',
        'font-size',
        'text-align',
        'text-color',
        'text-highlight',
        'horizontal-rule',
        'bullet',
        'link',
        'table',
        'image',
        'youtube',
    ])
);

$except = ['label', 'enabled', 'readonly', 'placeholder', 'class', 'buttons', 'suggestion', 'wire:model', 'wire:model.defer', 'wire:model.lazy'];
@endphp

<x-field :attributes="$attributes->merge([
    'field' => $field,
    'block' => true,
])->only(['field', 'block', 'for', 'no-label', 'label'])">
    <div
        wire:ignore
        x-cloak
        x-data="{
            content: @entangle($attributes->wire('model')),
            mode: @js($mode),
            lazy: @js($lazy),
            showButtons: @js($transparent ? false : true),

            init () {
                this.$watch('content', value => {
                    if (!this.editor()) return
                    if (value === this.getLatestContent()) return
                    this.commands().setContent(value, false)
                })
            },

            sync () {
                if (!this.editor().isEditable) return
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
                    placeholder: {{ Js::from(tr($placeholder)) }},
                    editable: {{ Js::from(!$readonly) }},
                    autofocus: {{ Js::from($autofocus) }},
                    editorProps: {
                        attributes: {
                            class: {{ Js::from(collect(['editor-content', $attributes->get('class')])->filter()->join(' ')) }}
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
        x-on:editor-focus="showButtons = true"
        @if ($transparent)
        x-on:click.away="showButtons = false"
        @endif
        x-modelable="content"
        {{ $attributes->except($except) }}>
        <div class="editor {{ $transparent ? 'editor-transparent' : '' }}">
            @if ($buttons)
                @if ($buttons->count() && !$readonly && $mode !== 'text')
                    <div
                        x-show="showButtons"
                        class="editor-buttons">
                        <div class="editor-buttons-container">
                            @foreach ($buttons as $button)
                                <x-dynamic-component :component="'editor.button.'.$button"/>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="editor-menu">
                    @if ($buttons->contains('link')) <x-editor.menu.link/> @endif
                    @if ($buttons->contains('table')) <x-editor.menu.table/> @endif
                    @if ($buttons->contains('image')) <x-editor.menu.image/> @endif
                    @if ($buttons->contains('youtube')) <x-editor.menu.youtube/> @endif
                </div>
            @endif

            @if ($suggestion)
                <x-editor.suggestion
                    :options="get($suggestion, 'options')"
                    :filters="get($suggestion, 'filters')">
                </x-editor.suggestion>
            @endif

            <div
                x-init="Editor($refs.editor, getEditorConfigs())"
                x-on:editor-update="!lazy && sync()"
                x-on:editor-blur="lazy && sync()"
                x-on:click.away="sync()"
                class="editor-container">
                <div x-ref="editor"></div>
            </div>
        </div>
    </div>
</x-field>
