@php
$field = $attributes->field();
$label = $attributes->get('no-label') ? null : $attributes->get('label');
$transparent = $attributes->get('transparent', false);
$mention = $mention ?? $attributes->get('mention', false);
$content = $attributes->get('content');
$submit = $attributes->submitAction() ?: 'submit';
$placeholder = $attributes->get('placeholder', 'app.label.say-something');
$except = ['label', 'transparent', 'content', 'mention', 'placeholder'];
@endphp

<x-field :attributes="$attributes->merge([
    'field' => $field,
    'block' => true,
])->only(['field', 'block', 'for', 'no-label', 'label'])">
    <div
        wire:ignore
        x-cloak
        x-data="{
            editor () {
                return this.$refs.editor?.editor
            },

            isEmpty () {
                let content = this.editor().getHTML()
                if (typeof content === 'string') return empty(content.striptags())
                else return empty(content)
            },

            submit () {
                if (this.isEmpty()) return

                this.$wire
                    .call({{ Js::from($submit) }}, {
                        content: this.editor().getHTML(),
                    })
                    .then(() => this.editor().chain().setContent('', false).focus().run())
            },
        }"
        x-on:keydown.enter.prevent="() => {
            if (!$event.shiftKey) {
                editor().commands.undo()
                submit()
            }
        }"
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
                                class: {{ Js::from(collect(['editor-chat-content', $attributes->get('class')])->filter()->join(' ')) }}
                            },
                        },
                    },
                    mentionTemplate: $root.querySelector('.editor-mention'),
                })"
                class="editor-container flex">
                <div class="grow">
                    <div x-ref="editor"></div>
                </div>

                <div class="shrink-0 self-end p-2">
                    <div class="flex items-center divide-x border rounded-md shadow">
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
                            ] as $item)
                                <x-editor.dropdown.item
                                    :label="get($item, 'label')"
                                    :icon="get($item, 'icon')"
                                    class="text-sm"
                                    x-on:click="editor().commands.{{ get($item, 'command') }}">
                                </x-editor.dropdown.item>
                            @endforeach            
                        </x-editor.dropdown>

                        <button
                            type="button"
                            x-tooltip.raw="{{ tr('app.label.attach') }}"
                            class="p-1.5 flex items-center justify-center">
                            <x-icon attach/>
                        </button>
    
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
        </div>
    </div>
</x-field>
