<atom:_dropdown>
    <atom:_editor.button label="text-color">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M15.2459 14H8.75407L7.15407 18H5L11 3H13L19 18H16.8459L15.2459 14ZM14.4459 12L12 5.88516L9.55407 12H14.4459ZM3 20H21V22H3V20Z"></path></svg>
    </atom:_editor.button>

    <x-slot:popover>
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
                <atom:icon close/>
            </div>
        </div>
    </x-slot:popover>
</atom:_dropdown>
