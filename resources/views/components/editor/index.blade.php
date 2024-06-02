@php
$for = $attributes->get('for') ?? $attributes->wire('model')->value();
$mode = $attributes->get('mode', 'html');
$label = $attributes->get('label');
$nolabel = $attributes->get('no-label');
$enabled = $attributes->get('enabled', false);
$readonly = $attributes->get('readonly', false);
$lazy = $attributes->modifier('lazy');
$placeholder = $attributes->get('placeholder', 'Write something...');
$except = ['label', 'enabled', 'readonly', 'placeholder', 'wire:model', 'wire:model.defer', 'wire:model.lazy'];
@endphp

<div>
    @if (!$nolabel)
        <div class="mb-2">
            <x-label :label="$label" :for="$for"/>
        </div>
    @endif

    <div
        wire:ignore
        x-cloak
        x-data="{
            content: @entangle($attributes->wire('model')),
            mode: @js($mode),
            lazy: @js($lazy),
            enabled: @js($enabled),
            readonly: @js($readonly),
            placeholder: @js(tr($placeholder)),

            init () {
                this.$watch('content', content => {
                    if (!this.editor()) return
                    if (content !== this.getLatestContent()) {
                        this.commands().setContent(this.getLatestContent(), false)
                    }
                })
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
        <template x-if="!enabled">
            <div x-on:click.stop="!readonly && (enabled = true)">
                <template x-if="!isEmpty() && mode === 'html'">
                    <div x-html="content" class="editor-content"></div>
                </template>

                <template x-if="!isEmpty() && mode === 'text'">
                    <div x-text="content" {{ $attributes->only('class') }}></div>
                </template>

                <template x-if="isEmpty()">
                    <input type="text" x-bind:placeholder="placeholder" class="transparent w-full" readonly>
                </template>
            </div>
        </template>

        <template x-if="enabled">
            <div
                x-init="Editor($refs.editor, { content, placeholder })"
                x-on:editor-update="!lazy && sync()"
                x-on:editor-blur="lazy && sync()"
                x-on:click.away="sync(); enabled = false"
                class="editor relative bg-white border border-gray-300 rounded-lg ring-1 ring-theme">
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
    </div>
</div>