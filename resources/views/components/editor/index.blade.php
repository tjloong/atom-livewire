@php
$field = $attributes->field();
$mode = $attributes->get('mode', 'html');
$label = $attributes->get('no-label') ? null : $attributes->get('label');
$readonly = $attributes->get('readonly', false);
$autofocus = $attributes->get('autofocus', false);
$transparent = $attributes->get('transparent', false);
$mention = $mention ?? $attributes->get('mention', false);
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

$except = ['label', 'enabled', 'readonly', 'placeholder', 'class', 'buttons', 'mention', 'wire:model', 'wire:model.defer', 'wire:model.lazy'];
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
            mode: {{ Js::from($mode) }},
            lazy: {{ Js::from($lazy) }},
            transparent: {{ Js::from($transparent) }},

            init () {
                if (this.transparent) this.$refs.buttons.addClass('hidden')
    
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
        x-on:editor-focus="() => transparent && $refs.buttons.removeClass('hidden')"
        x-on:click.away="() => transparent && $refs.buttons.addClass('hidden')"
        x-modelable="content"
        {{ $attributes->except($except) }}>
        <div class="{{ pick([
            'editor' => !$transparent,
            'editor editor-transparent' => $transparent,
        ]) }}">
            @if ($buttons)
                @if ($buttons->count() && !$readonly && $mode !== 'text')
                    <div x-ref="buttons" class="editor-buttons">
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
                        content,
                        placeholder: {{ Js::from(tr($placeholder)) }},
                        editable: {{ Js::from(!$readonly) }},
                        autofocus: {{ Js::from($autofocus) }},
                        editorProps: {
                            attributes: {
                                class: {{ Js::from(collect(['editor-content', $attributes->get('class')])->filter()->join(' ')) }}
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
</x-field>
