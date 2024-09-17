<x-page wire:close="$emit('closePage')" class="max-w-screen-lg">
@if ($page)
    <x-form>
        <div class="flex flex-col divide-y">
            <div class="p-5 flex flex-col gap-2">
                <x-textarea
                    wire:model.defer="page.title"
                    placeholder="app.label.title"
                    class="text-xl font-bold"
                    no-label
                    transparent>
                </x-textarea>

                <x-editor
                    wire:model.defer="page.content"
                    no-label
                    transparent>
                </x-editor>
            </div>

            <x-inputs>
                <x-input wire:model.defer="page.slug" label="app.label.slug"/>
            </x-inputs>
        </div>
    </x-form>
@endif
</x-page>