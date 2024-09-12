<x-editor.dropdown label="app.label.list">
    <x-slot:icon>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 4H21V6H8V4ZM3 3.5H6V6.5H3V3.5ZM3 10.5H6V13.5H3V10.5ZM3 17.5H6V20.5H3V17.5ZM8 11H21V13H8V11ZM8 18H21V20H8V18Z"></path></svg>
    </x-slot:icon>

    @foreach ([
        [
            'label' => 'app.label.bullet-list',
            'command' => 'toggleBulletList()',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 4H21V6H8V4ZM3 3.5H6V6.5H3V3.5ZM3 10.5H6V13.5H3V10.5ZM3 17.5H6V20.5H3V17.5ZM8 11H21V13H8V11ZM8 18H21V20H8V18Z"></path></svg>',
        ],
        [
            'label' => 'app.label.ordered-list',
            'command' => 'toggleOrderedList()',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 4H21V6H8V4ZM5 3V6H6V7H3V6H4V4H3V3H5ZM3 14V11.5H5V11H3V10H6V12.5H4V13H6V14H3ZM5 19.5H3V18.5H5V18H3V17H6V21H3V20H5V19.5ZM8 11H21V13H8V11ZM8 18H21V20H8V18Z"></path></svg>',
        ],
        [
            'label' => 'app.label.indent',
            'command' => 'sinkListItem("listItem")',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 19H21V21H3V19ZM11 14H21V16H11V14ZM11 9H21V11H11V9ZM3 12.5L7 9V16L3 12.5Z"></path></svg>',
        ],
        [
            'label' => 'app.label.outdent',
            'command' => 'liftListItem("listItem")',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4H21V6H3V4ZM3 19H21V21H3V19ZM11 14H21V16H11V14ZM11 9H21V11H11V9ZM7 12.5L3 16V9L7 12.5Z"></path></svg>',
        ],
    ] as $btn)
        <x-editor.dropdown.item
            :label="get($btn, 'label')"
            x-on:click="commands().{{ get($btn, 'command') }}">
            <x-slot:icon>
                {!! get($btn, 'icon') !!}
            </x-slot:icon>
        </x-editor.dropdown.item>
    @endforeach
</x-editor.dropdown>
