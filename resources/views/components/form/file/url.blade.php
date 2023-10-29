@php
    $accept = $attributes->get('accept');
    $multiple = $attributes->get('multiple', false);
    $acceptImage = str($accept)->is('*image/*');
    $acceptYoutube = str($accept)->is('*youtube*');
@endphp

<div 
    x-data="{
        text: null,
        submit () {
            axios.post(@js(route('__file.url')), { url: this.text.split(`\n`) })
                .then(res => {
                    this.$dispatch('files-created', res.data)
                    this.text = null
                })
        },
    }"
    class="flex flex-col gap-2">
    <x-form.field :label="collect([
        !$accept || $acceptImage ? tr('common.label.image-url') : null,
        !$accept || $acceptYoutube ? tr('common.label.youtube-url') : null,
    ])->filter()->join(' / ')">
        @if ($multiple) <textarea x-model="text" x-on:input.stop class="form-input w-full" rows="5"></textarea>
        @else <input type="text" x-model="text" x-on:input.stop class="form-input w-full">
        @endif
    </x-form.field>

    <div>
        <x-button color="green" icon="add" sm
            label="common.label.add"
            x-on:click="submit"/>
    </div>
</div>