<x-editor.dropdown label="app.label.text-align">
    <x-slot:icon>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 19H17V21H3V19ZM3 14H21V16H3V14ZM3 9H17V11H3V9Z"></path></svg>
    </x-slot:icon>

    @foreach ([
        [
            'label' => 'app.label.align-left',
            'command' => 'left',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 19H17V21H3V19ZM3 14H21V16H3V14ZM3 9H17V11H3V9Z"></path></svg>',
        ],
        [
            'label' => 'app.label.align-center',
            'command' => 'center',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM5 19H19V21H5V19ZM3 14H21V16H3V14ZM5 9H19V11H5V9Z"></path></svg>',
        ],
        [
            'label' => 'app.label.align-right',
            'command' => 'right',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM7 19H21V21H7V19ZM3 14H21V16H3V14ZM7 9H21V11H7V9Z"></path></svg>',
        ],
        [
            'label' => 'app.label.align-justify',
            'command' => 'justify',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 19H21V21H3V19ZM3 14H21V16H3V14ZM3 9H21V11H3V9Z"></path></svg>',
        ],
    ] as $item)
        <x-editor.dropdown.item
            :label="get($item, 'label')"
            x-on:click="commands().setTextAlign({{ Js::from(get($item, 'command')) }}); close()">
            <x-slot:icon>
                {!! get($item, 'icon') !!}
            </x-slot:icon>
        </x-editor.dropdown.item>
    @endforeach
</x-editor.dropdown>
