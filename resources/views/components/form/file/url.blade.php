@php
    $accept = $attributes->get('accept');
    $multiple = $attributes->get('multiple', false);
    $acceptImage = str($accept)->is('*image/*');
    $acceptYoutube = str($accept)->is('*youtube*');
@endphp

<div 
    x-data="{
        text: null,
        endpoint: @js(route('__file.url')),

        submit () {
            ajax(this.endpoint).post({ url: this.text.split(`\n`) }).then(res => {
                this.$dispatch('files-created', res)
                this.text = null
            })
        },
    }"
    x-on:input.stop
    class="flex flex-col gap-2">
    <x-form.field :label="collect([
        !$accept || $acceptImage ? tr('app.label.image-url') : null,
        !$accept || $acceptYoutube ? tr('app.label.youtube-url') : null,
    ])->filter()->join(' / ')">
        @if ($multiple)
            <div class="relative w-full">
                <textarea x-model="text" class="form-input w-full" rows="3"></textarea>
                <div class="absolute top-0 right-0 z-10 p-2">
                    <x-button xs color="green" icon="check" label="app.label.add"/>
                </div>
            </div>
        @else
            <x-form.text x-model="text" x-on:keydown.enter.prevent="submit">
                <x-slot:button label="app.label.add" icon="add" x-on:click="submit()"></x-slot:button>
            </x-form.text>
        @endif
    </x-form.field>
</div>