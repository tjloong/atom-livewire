<div class="group">
    <x-editor.dropdown icon="heading" tooltip="app.label.heading">
        <div class="flex flex-col divide-y first:*:rounded-t-lg last:*:rounded-b-lg">
            @foreach ([1, 2, 3, 4] as $n)
                <div
                    x-tooltip.raw="{{ tr('app.label.heading-'.$n) }}"
                    x-on:click="commands().toggleHeading({ level: @js($n) }); close()"
                    class="py-1.5 px-3 cursor-pointer font-bold hover:bg-slate-50">
                    H{{ $n }}
                </div>                
            @endforeach

            <div
                class="py-1.5 px-3 cursor-pointer hover:bg-slate-50"
                x-tooltip.raw="{{ tr('app.label.paragraph') }}"
                x-on:click="commands().setParagraph(); close()">
                <x-icon name="paragraph"/>
            </div>
        </div>
    </x-editor.dropdown>
</div>