@php
$label = $attributes->get('label');
$inline = $attributes->get('inline');
$caption = $attributes->get('caption');
$readonly = $attributes->get('readonly', false);
$autofocus = $attributes->get('autofocus', false);
$transparent = $attributes->get('transparent', false);
$mention = $mention ?? $attributes->get('mention', false);
$lazy = $attributes->modifier('lazy');
$placeholder = $attributes->get('placeholder', 'write-something');

$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;

$buttons = collect($attributes->get('buttons') ?? [
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
]);

$attrs = $attributes
    ->except([
        'label', 'inline', 'caption', 'enabled', 'readonly', 'placeholder',
        'class', 'buttons', 'mention', 'wire:model', 'wire:model.defer', 'wire:model.lazy',
    ]);
@endphp

@if ($label || $caption)
    <atom:_input.field
        :label="$label"
        :caption="$caption"
        :inline="$inline"
        :required="$required"
        :error="$error">
        <atom:_editor :attributes="$attributes->except(['label', 'caption', 'error', 'inline'])"/>
    </atom:_input.field>
@else
    <div
        wire:ignore
        x-cloak
        x-data="{
            content: @entangle($attributes->wire('model')),
            lazy: {{ js($lazy) }},
            transparent: {{ js($transparent) }},

            init () {
                if (this.transparent) this.$refs.buttons.addClass('hidden')

                this.$watch('content', value => {
                    if (!this.editor()) return
                    if (value === this.getHtmlContent()) return
                    this.commands().setContent(value, false)
                })
            },

            sync () {
                if (!this.editor().isEditable) return
                if (this.editor().isEmpty) this.content = null
                else this.content = this.getHtmlContent()
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

            getHtmlContent () {
                return this.editor().getHTML()
            },

            getJsonContent () {
                return this.editor().getJson()
            },

            isEmpty () {
                if (typeof this.content === 'string') return empty(this.content.striptags())
                else return empty(this.content)
            },
        }"
        x-on:editor-focus="() => transparent && $refs.buttons.removeClass('hidden')"
        x-on:click.away="() => transparent && $refs.buttons.addClass('hidden')"
        x-modelable="content"
        {{ $attrs }}>
        <div x-bind:class="transparent && 'editor-transparent'" class="editor">
            @if ($buttons)
                @if ($buttons->count() && !$readonly)
                    <div x-ref="buttons" class="editor-buttons">
                        <div class="editor-buttons-container">
                            @foreach ($buttons as $button)
                                <x-dynamic-component :component="'_editor.button.'.$button"/>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="editor-menu">
                    @if ($buttons->contains('link')) <atom:_editor.menu.link/> @endif
                    @if ($buttons->contains('table')) <atom:_editor.menu.table/> @endif
                    @if ($buttons->contains('image')) <atom:_editor.menu.image/> @endif
                    @if ($buttons->contains('youtube')) <atom:_editor.menu.youtube/> @endif
                </div>
            @endif

            @if ($mention instanceof \Illuminate\View\ComponentSlot)
                <atom:_editor.mention
                    :options="$mention->attributes->get('options', [])"
                    :filters="$mention->attributes->get('filters', [])">
                    {{ $mention }}
                </atom:_editor.mention>
            @elseif (is_string($mention))
                <atom:_editor.mention :options="$mention"/>
            @elseif ($mention)
                <atom:_editor.mention :options="get($mention, 'options')" :filters="get($mention, 'filters')"/>
            @endif

            <div
                x-init="Editor({
                    element: $refs.editor,
                    tiptapConfig: {
                        content,
                        placeholder: {{ js(t($placeholder).'...') }},
                        editable: {{ js(!$readonly) }},
                        autofocus: {{ js($autofocus) }},
                        editorProps: {
                            attributes: {
                                class: {{ js(collect(['editor-content', $attributes->get('class')])->filter()->join(' ')) }}
                            },
                        },
                    },
                    bubbleMenus: {
                        linkMenu: $root.querySelector('.editor-menu .link-menu'),
                        imageMenu: $root.querySelector('.editor-menu .image-menu'),
                        tableMenu: $root.querySelector('.editor-menu .table-menu'),
                        youtubeMenu: $root.querySelector('.editor-menu .youtube-menu'),
                    },
                    mentionTemplate: $root.querySelector('.editor-mention'),
                })"
                x-on:editor-update="!lazy && sync()"
                x-on:editor-blur="lazy && sync()"
                x-on:click.away="sync()"
                class="editor-container">
                <div x-ref="editor"></div>
            </div>
        </div>
    </div>
@endif
