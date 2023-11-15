@php
    $multiple = $attributes->get('multiple', false);
    $enabledUrl = $attributes->get('url') ?? $attributes->get('enable-url') ?? false;
    $enabledLibrary = $attributes->get('library') ?? $attributes->get('enable-library') ?? false;
@endphp

<x-form.field {{ $attributes }}>
    <div class="relative border rounded-lg flex flex-col divide-y overflow-hidden">
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <x-form.file.listing {{ $attributes }}/>
        @endif

        <div wire:ignore x-cloak
            x-data="{
                value: @entangle($attributes->wire('model')),
                multiple: @js($multiple),
                isUrlMode: false,
                showLibrary: false,
                input (files) {
                    const value = files.map(val => (val.id))
                    
                    if (this.multiple) {
                        value.forEach(val => {
                            const index = this.value.indexOf(val)
                            if (index === -1) this.value.push(val)
                        })
                    }
                    else if (value.length) this.value = value[0]
                    else this.value = null

                    this.isUrlMode = false
                    this.showLibrary = false
                },
            }"
            x-on:files-created="input($event.detail)"
            x-on:files-selected="input($event.detail)"
            x-on:files-uploaded="input($event.detail)"
            class="w-full bg-slate-100">
            @if ($enabledUrl)
                <div x-show="isUrlMode" class="p-4 flex flex-col gap-4">
                    <x-form.file.url {{ $attributes }}/>
                    <x-link icon="back" label="common.label.back" class="text-sm"
                        x-on:click="isUrlMode = false"/>
                </div>
            @endif

            <div x-show="!isUrlMode" class="p-4 flex flex-col gap-4">
                <x-form.file.uploader {{ $attributes }}/>

                @if ($enabledUrl || $enabledLibrary)
                    <div class="flex items-center divide-x divide-gray-300 text-sm">
                        @if ($enabledLibrary)
                            <x-link icon="search" class="pr-3"
                                label="common.label.browse-library"
                                x-on:click="showLibrary = true"/>
                        @endif

                        @if ($enabledUrl)
                            <x-link icon="code" class="pl-3"
                                label="common.label.get-from-url"
                                x-on:click="isUrlMode = true"/>
                        @endif
                    </div>
                @endif
            </div>

            @if ($enabledLibrary)
                <x-form.file.library {{ $attributes }}/>
            @endif
        </div>
    </div>
</x-form.field>