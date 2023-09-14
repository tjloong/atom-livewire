<x-form.field {{ $attributes }}>
    <div class="relative border rounded-lg flex flex-col divide-y overflow-hidden">
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <x-form.file.listing {{ $attributes }}/>
        @endif

        <div
            x-data="{
                value: @entangle($attributes->wire('model')),
                multiple: @js($attributes->get('multiple', false)),
                isUrlMode: false,
                openLibrary () {
                    $el.querySelector('#file-input-library').dispatchEvent(
                        new CustomEvent('open-library', { bubble: false })
                    )
                },
                input (files) {
                    const value = files.map(val => (val.id))
                    
                    if (this.multiple) this.value = value
                    else if (value.length) this.value = value[0]
                    else this.value = null
                },
            }"
            x-on:files-created="input($event.detail)"
            x-on:files-selected="input($event.detail)"
            x-on:files-uploaded="input($event.detail)"
            wire:ignore
            class="w-full bg-slate-100">
            <template x-if="isUrlMode">
                <x-form.file.url 
                    x-on:disable-url="isUrlMode = false" 
                    {{ $attributes }}/>
            </template>
    
            <template x-if="!isUrlMode">
                <x-form.file.uploader 
                    x-on:enable-url="isUrlMode = true"
                    x-on:browse-library="openLibrary"
                    {{ $attributes }}/>
            </template>
    
            <x-form.file.library {{ $attributes }}/>
        </div>
    </div>
</x-form.field>