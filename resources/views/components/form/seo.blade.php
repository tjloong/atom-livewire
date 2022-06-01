<div 
    x-data="{
        value: @entangle($attributes->wire('model')),
        title: null,
        description: null,
        image: null,
        init () {
            this.title = this.value?.title || null
            this.description = this.value?.description || null
            this.image = this.value?.image || null
        },
        input () {
            this.value = {
                title: this.title || null,
                description: this.description || null,
                image: this.image || null,
            }
        },
    }"
    x-init="init"
    class="grid gap-6"
>
    <x-form.text 
        label="Meta Title"
        x-model="title" 
        x-on:input="input"
        caption="Recommended title length is 50 ~ 60 characters"
    />

    <x-form.textarea 
        label="Meta Description"
        x-model="description" 
        x-on:input="input"
        caption="Recommended description length is 155 ~ 160 characters"
    />

    <x-form.text 
        label="Meta Image URL"
        x-on:input="input"
        x-model="image"
    />
</div>
