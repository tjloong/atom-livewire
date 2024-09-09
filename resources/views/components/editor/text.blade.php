<div class="group">
    @foreach ([
        ['icon' => 'bold',          'label' => 'app.label.bold',          'command' => 'toggleBold()'],
        ['icon' => 'italic',        'label' => 'app.label.italic',        'command' => 'toggleItalic()'],
        ['icon' => 'underline',     'label' => 'app.label.underline',     'command' => 'toggleUnderline()'],
        ['icon' => 'strikethrough', 'label' => 'app.label.strikethrough', 'command' => 'toggleStrike()'],
        ['icon' => 'subscript',     'label' => 'app.label.subscript',     'command' => 'toggleSubscript()'],
        ['icon' => 'superscript',   'label' => 'app.label.superscript',   'command' => 'toggleSuperscript()'],
    ] as $btn)
        <x-editor.button
            :label="get($btn, 'label')"
            :icon="get($btn, 'icon')"
            x-on:click="commands().{{ get($btn, 'command') }}">
        </x-editor.button>
    @endforeach

    <x-editor.dropdown icon="font" tooltip="app.label.font-size">
        <div class="flex flex-col divide-y last:*:rounded-b-lg first:*:rounded-t-lg">
            @foreach (['xs', 'sm', 'md', 'lg', 'xl'] as $size)
                <div
                    x-on:click="commands().setFontSize(@js($size)); close()"
                    class="py-1.5 px-3 font-bold cursor-pointer hover:bg-slate-50">
                    {{ $size }}
                </div>
            @endforeach

            <div class="p-2">
                <input type="number"
                    class="appearance-none text-sm w-20"
                    placeholder="{{ tr('app.label.size') }}"
                    step=".1"
                    max="999"
                    x-on:input.stop
                    x-on:keydown.enter.prevent="() => {
                        let val = +$event.target.value
                        if (val < 999) {
                            commands().setFontSize(`${val}px`)
                            $event.target.value = ''
                        }
                        close()
                    }">
            </div>
        </div>
    </x-editor.dropdown>

    <x-editor.dropdown icon="align-left" tooltip="app.label.text-align">
        <div class="flex flex-col divide-y first:*:rounded-t-lg last:*:rounded-b-lg">
            @foreach ([
                ['icon' => 'align-left',    'label' => 'app.label.align-left',    'command' => 'left'],
                ['icon' => 'align-center',  'label' => 'app.label.align-center',  'command' => 'center'],
                ['icon' => 'align-right',   'label' => 'app.label.align-right',   'command' => 'right'],
                ['icon' => 'align-justify', 'label' => 'app.label.align-justify', 'command' => 'justify'],
            ] as $btn)
                <div
                    x-tooltip.raw="{{ tr(get($btn, 'label')) }}"
                    x-on:click="commands().setTextAlign(@js(get($btn, 'command'))); close()"
                    class="py-1.5 px-3 font-bold cursor-pointer hover:bg-slate-50">
                    <x-icon :name="get($btn, 'icon')"/>
                </div>
            @endforeach        
        </div>
    </x-editor.dropdown>

    <x-editor.dropdown icon="droplet" tooltip="app.label.text-color">
        <div class="grow grid grid-cols-11 gap-1 p-2 max-h-[300px] overflow-auto">
            @foreach (color()->all() as $color)
                <div
                    x-on:click="commands().setColor(@js($color)); close()"
                    x-bind:style="{ backgroundColor: @js($color) }"
                    class="cursor-pointer w-6 h-6 border rounded hover:ring-1 hover:ring-offset-1 hover:ring-gray-500">
                </div>
            @endforeach

            <div
                x-on:click="commands().unsetColor(); close()"
                class="cursor-pointer w-6 h-6 border border-red-500 rounded flex items-center justify-center text-red-500 hover:ring-1 hover:ring-offset-1 hover:ring-gray-500">
                <x-icon name="xmark"/>
            </div>
        </div>
    </x-editor.dropdown>

    <x-editor.button
        label="app.label.text-highlight"
        icon="highlighter"
        x-on:click="commands().setHighlight()">
    </x-editor.button>
</div>    
