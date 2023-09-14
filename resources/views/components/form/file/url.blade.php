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
            this.$wire.createFilesFromUrls(this.text.split(`\n`)).then((res) => {
                this.$dispatch('files-created', res)
                this.text = null
            })
        },
    }"
    class="flex flex-col gap-2 p-4"
    {{ $attributes->whereStartsWith('x-') }}>
    <x-form.field :label="collect([
        !$accept || $acceptImage ? 'Image URL' : null,
        !$accept || $acceptYoutube ? 'Youtube URL' : null,
    ])->filter()->join(' / ')">
        @if ($multiple) <textarea x-model="text" x-on:input.stop class="form-input w-full" rows="5"></textarea>
        @else <input type="text" x-model="text" x-on:input.stop class="form-input w-full">
        @endif
    </x-form.field>

    <div>
        <x-button x-on:click="submit" label="Add URL" sm/>
        <x-button x-on:click="$dispatch('disable-url')" label="Cancel" color="gray" sm/>
    </div>
</div>