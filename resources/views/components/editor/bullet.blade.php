<div class="group">
    @foreach ([
        ['icon' => 'list-ul', 'label' => 'app.label.bullet-list', 'command' => 'toggleBulletList()'],    
        ['icon' => 'list-ol', 'label' => 'app.label.ordered-list', 'command' => 'toggleOrderedList()'],    
        ['icon' => 'indent', 'label' => 'app.label.sink-list-item', 'command' => 'sinkListItem("listItem")'],    
        ['icon' => 'outdent', 'label' => 'app.label.lift-list-item', 'command' => 'liftListItem("listItem")'],
    ] as $btn)
        <x-editor.button
            :label="get($btn, 'label')"
            :icon="get($btn, 'icon')"
            x-on:click="commands().{{ get($btn, 'command') }}">
        </x-editor.button>
    @endforeach
</div>
