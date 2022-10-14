<x-modal :uid="$uid" :header="$title" class="max-w-screen-md">
    <div x-data
        x-on:{{ $uid }}-open.window="$wire.set('open', true)"
        x-on:{{ $uid }}-close.window="$wire.set('open', false)"
        class="flex flex-col gap-6"
    >
        @if ($open)
            <x-tab wire:model="tab">
                @foreach ($this->tabs as $item)
                    <x-tab.item
                        :name="data_get($item, 'name')"
                        :label="data_get($item, 'label')"
                    />
                @endforeach
            </x-tab>
        @endif

        @livewire(lw('app.file.uploader.'.$tab), [
            'accept' => $accept,
            'private' => $private,
            'multiple' => $multiple,
            'inputFileTypes' => $this->inputFileTypes,
        ], key($tab))
    </div>
</x-modal>
