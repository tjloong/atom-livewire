<div x-data="{
    value: @entangle($attributes->wire('model')),
}" class="grid gap-6">
    <x-form.text 
        label="Meta Title"
        x-model="value.title" 
        caption="Recommended title length is 50 ~ 60 characters"
    />

    <x-form.textarea 
        label="Meta Description"
        x-model="value.description" 
        caption="Recommended description length is 155 ~ 160 characters"
    />

    <x-form.text 
        label="Meta Image URL"
        x-model="value.image"
    />
</div>
