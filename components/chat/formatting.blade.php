<atom:_dropdown>
    <button type="button"
        x-tooltip="{{ js(t('text-formatting')) }}"
        class="p-1.5 flex items-center justify-center">
        <atom:icon bold size="15"/>
    </button>

    <x-slot:content>
        @foreach ([
            [
                'label' => 'bold',
                'icon' => 'bold',
                'command' => 'toggleBold()',
            ],
            [
                'label' => 'italic',
                'icon' => 'italic',
                'command' => 'toggleItalic()',
            ],
            [
                'label' => 'underline',
                'icon' => 'underline',
                'command' => 'toggleBold()',
            ],
            [
                'label' => 'strikethrough',
                'icon' => 'strikethrough',
                'command' => 'toggleStrike()',
            ],
            [
                'label' => 'bullet-list',
                'icon' => 'unordered-list',
                'command' => 'toggleBulletList()',
            ],
            [
                'label' => 'ordered-list',
                'icon' => 'ordered-list',
                'command' => 'toggleOrderedList()',
            ],
        ] as $item)
            <atom:menu-item
                :icon="get($item, 'icon')"
                x-on:click="editor().commands.{{ get($item, 'command') }}">
                @t(get($item, 'label'))
            </atom:menu-item>
        @endforeach
    </x-slot:content>
</atom:_dropdown>
