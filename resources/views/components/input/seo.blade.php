<x-input.field>
    <div x-data="inputSeo($wire.get('{{ $attributes->wire('model')->value() }}'))">
        <div {{ $attributes }} x-on:seo-updated.window="$dispatch('input', $event.detail)"></div>

        <x-input.text x-model="value.title" caption="Recommended title length is 50 ~ 60 characters">
            Meta Title
        </x-input.text>

        <x-input.textarea x-model="value.description" caption="Recommended description length is 155 ~ 160 characters">
            Meta Description
        </x-input.textarea>

        <x-input.text x-model="value.image">
            Meta Image URL
        </x-input.text>
    </div>
</x-input.field>