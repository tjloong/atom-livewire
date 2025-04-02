<atom:_dropdown>
    <atom:_editor.button label="text-align">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 19H17V21H3V19ZM3 14H21V16H3V14ZM3 9H17V11H3V9Z"></path></svg>
    </atom:_editor.button>

    <x-slot:popover>
        @foreach ([
            [
                'label' => 'align-left',
                'command' => 'left',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 19H17V21H3V19ZM3 14H21V16H3V14ZM3 9H17V11H3V9Z"></path></svg>',
            ],
            [
                'label' => 'align-center',
                'command' => 'center',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM5 19H19V21H5V19ZM3 14H21V16H3V14ZM5 9H19V11H5V9Z"></path></svg>',
            ],
            [
                'label' => 'align-right',
                'command' => 'right',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM7 19H21V21H7V19ZM3 14H21V16H3V14ZM7 9H21V11H7V9Z"></path></svg>',
            ],
            [
                'label' => 'align-justify',
                'command' => 'justify',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 19H21V21H3V19ZM3 14H21V16H3V14ZM3 9H21V11H3V9Z"></path></svg>',
            ],
        ] as $item)
            <atom:menu-item x-on:click="commands().setTextAlign({{ js(get($item, 'command')) }})">
                <div class="flex items-center gap-3">
                    @ee(get($item, 'icon'))
                    <div class="grow">@t(get($item, 'label'))</div>
                </div>
            </atom:menu-item>
        @endforeach
    </x-slot:popover>
</atom:_dropdown>
