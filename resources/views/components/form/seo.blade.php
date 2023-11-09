<div
    x-data="{
        seo: @entangle($attributes->wire('model')),
    }"
    x-modelable="seo"
    {{ $attributes }}>
    <div x-on:input.stop class="flex flex-col gap-4">
        <x-form.text x-model="seo.title"
            label="common.label.meta-title"
            caption="common.label.recommended-meta-title-length"/>
    
        <x-form.textarea x-model="seo.description"
            label="common.label.meta-description"
            caption="common.label.recommended-meta-description-length"/>
    
        <x-form.text x-model="seo.image" label="common.label.meta-image"/>
    </div>
</div>