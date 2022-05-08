<div x-data="seoInput(@js([
    'model' => $attributes->wire('model')->value(),
    'value' => $attributes->get('value'),
]))" class="grid gap-6">
    <template {{ $attributes->whereStartsWith('wire') }} 
        x-on:seo-updated.window="$dispatch('input', $event.detail)"
    ></template>

    <x-form.text 
        label="Meta Title"
        x-model="value.title" 
        x-on:input="input"
        caption="Recommended title length is 50 ~ 60 characters"
    />

    <x-form.textarea 
        label="Meta Description"
        x-model="value.description" 
        x-on:input="input"
        caption="Recommended description length is 155 ~ 160 characters"
    />

    <x-form.text 
        label="Meta Image URL"
        x-model="value.image"
        x-on:input="input"
    />
</div>
