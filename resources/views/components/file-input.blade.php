@php
$field = $attributes->field();
$inline = $attributes->get('inline', false);
$library = $attributes->get('library', false);
$caption = $caption ?? $attributes->get('caption');
$error = $error ?? $attributes->get('error');
$max = $attributes->get('max') ?? config('atom.max_upload_size');
$accept = $attributes->get('accept');
$multiple = $attributes->get('multiple');
$wire = $attributes->wire('model')->value();
$files = collect();

if ($id = (array) get($this, $wire)) {
    $files = model('file')->whereIn('id', $id)->take(1000)->get();
}
@endphp

<x-field :class="$inline ? 'items-center' : null" :attributes="$attributes->merge([
    'field' => $field,
    'inline' => $inline,
])->only(['inline', 'field', 'for', 'no-label', 'label'])">
    <div
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            loading: false,
            progress: null,
            config: {
                max: @js($max),
                accept: @js($accept),
                multiple: @js($multiple),
            },

            read (files) {
                this.loading = true
    
                upload(files, {
                    ...this.config,
                    progress: (value) => this.progress = value,
                })
                    .then(res => {
                        this.value = res.id
                        this.$dispatch('uploaded', res.files)
                        Livewire?.emit('uploaded', res.files)
                    })
                    .catch(({ message }) => $dispatch('alert', { title: tr('app.label.unable-to-upload'), message, type: 'error' }))
                    .finally(() => this.loading = false)
            },

            paste (items) {
                let files = Array.from(items).filter(item => (item.kind === 'file')).map(item => (item.getAsFile()))
                if (files.length) this.read(files)
            },

            remove (id) {
                if (this.config.multiple) {

                }
                else this.value = null
            },
        }"
        x-on:paste.stop="paste($event.clipboardData.items)"
        tabindex="0"
        class="relative bg-gray-100 rounded-lg border overflow-hidden ring-offset-1 focus-within:ring-1 group-has-[.error]/field:border-red-500 group-has-[.error]/field:ring-red-300">
        <x-file-dropzone x-on:input.stop="value = $event.detail"/>

        @if ($files->count() === 1 && $files->first()->is_image)
            <div class="bg-white border-b p-4">
                <div class="group w-24 relative">
                    <x-file :file="$files->first()" no-label lg/>
                    <div
                        x-on:click.stop="remove({{ $files->first()->id }})"
                        class="absolute inset-0 bg-black/70 rounded-lg items-center justify-center text-2xl text-white cursor-pointer hidden group-hover:flex">
                        <x-icon name="xmark"/>
                    </div>
                </div>
            </div>
        @else
            <div class="group bg-white border-b flex flex-col divide-y">
                @foreach ($files as $file)
                    <div class="p-4 flex gap-3 cursor-pointer">
                        <div class="grow" wire:click="$emit('editFile', {{ $file->id }})">
                            <x-file :file="$file"/>
                        </div>

                        <div
                            x-on:click.stop="remove({{ $file->id }})"
                            class="shrink-0 text-red-500 hidden group-hover:block">
                            <x-icon name="xmark"/>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="py-3 px-4">
            <div class="flex items-center gap-3 font-medium">
                <div
                    x-on:click="$refs.fileinput.click()"
                    class="text-black underline decoration-dotted flex items-center gap-2 py-1 cursor-pointer">
                    <x-icon name="upload" class="text-gray-500"/> {{ tr('app.label.browse-device') }}
                </div>

                @if ($library)
                    <span class="text-gray-500"> / </span>
                    <div
                        x-on:click.stop="Livewire.emit('showFilesLibrary')"
                        class="text-black underline decoration-dotted flex items-center gap-2 py-1 lowercase cursor-pointer">
                        {{ tr('app.label.or-browse-library') }}
                    </div>
                @endif
            </div>

            <div class="font-medium text-gray-500">
                {{ tr('app.label.or-drop-to-upload', ['max' => $max.'MB']) }}
            </div>

            <div class="font-medium text-gray-400">
                {{ tr('app.label.or-paste-to-upload') }}
            </div>

            <input type="file" class="hidden"
                x-ref="fileinput"
                x-on:change="read($event.target.files)"
                {{ $attributes->only(['accept', 'multiple']) }}>

            <div x-show="loading" class="absolute inset-0 bg-white/70 flex items-center justify-center p-2">
                <div class="bg-black/70 rounded-md flex items-center justify-center gap-2 py-2 px-4 z-1 shadow-lg">
                    <x-spinner size="20" class="text-theme-light"/>
                    <div class="text-white">
                        {{ tr('app.label.uploading') }} <span x-text="progress"></span>...
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($caption)
        <div class="mt-2">
            @if ($caption instanceof \Illuminate\View\ComponentSlot)
                {{ $caption }}
            @else
                <div class="text-sm text-gray-500">
                    {!! tr($caption) !!}
                </div>
            @endif
        </div>
    @endif

    @if ($field)
        <x-error :field="$field" class="mt-2"/>
    @elseif ($error instanceof \Illuminate\View\ComponentSlot)
        {{ $error }}
    @elseif ($error)
        <x-error :label="$error" class="mt-2"/>
    @endif
</x-field>