<div
    x-data="{ value: @entangle($attributes->wire('model')) }"
    x-modelable="value">
    <x-inputs>
        <x-input 
            x-model="value.title"
            label="app.label.meta-title"
            caption="app.label.recommended-meta-title-length">
        </x-input>

        <x-textarea
            x-model="value.description"
            label="app.label.meta-description"
            caption="app.label.recommended-meta-description-length">
        </x-textarea>

        <x-input
            x-model="value.image"
            label="app.label.meta-image">
        </x-input>
    </x-inputs>
</div>
