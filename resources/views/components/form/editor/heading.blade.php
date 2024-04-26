<div class="group">
    <x-form.editor.dropdown icon="heading" tooltip="Heading">
        <div class="flex flex-col items-center justify-center p-2">
            @foreach ([1, 2, 3, 4] as $n)
                <div class="cursor-pointer p-1 font-bold" 
                    x-tooltip.text="Heading {{ $n }}"
                    x-on:click="commands().toggleHeading({ level: @js($n) }); close()">
                    H{{ $n }}
                </div>
            @endforeach

            <div class="cursor-pointer p-1" x-tooltip.text="Paragraph" x-on:click="commands().setParagraph(); close()">
                <x-icon name="paragraph"/>
            </div>
        </div>
    </x-form.editor.dropdown>
</div>