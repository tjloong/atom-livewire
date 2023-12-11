@php
    $accept = $attributes->get('accept');
    $multiple = $attributes->get('multiple');
    $sortable = $attributes->get('sortable');
    $model = $attributes->wire('model')->value();
    $value = data_get($this, $model);
    $files = $value ? model('file')->whereIn('id', (array) $value)->get() : null;
@endphp

@if ($files && $files->count())
    <div wire:key="file-input-listing"
        x-data="{
            value: @entangle($model),
            multiple: @js($multiple),
            sortable: @js($sortable),
            checkboxes: [],
            init () {
                if (this.sortable) new Sortable(this.$refs.sortable, { onSort: () => this.sorted() })
            },
            select (id) {
                const index = this.checkboxes.indexOf(id)
                if (index > -1) this.checkboxes.splice(index, 1)
                else this.checkboxes.push(id)
            },
            remove (id = null) {
                if (id) {
                    const index = this.value.indexOf(id)
                    this.value.splice(index, 1)
                }
                else if (this.multiple) {
                    this.checkboxes.forEach((id) => this.remove(id))
                    this.checkboxes = []
                }
                else this.value = null
            },
            sorted () {
                const data = Array.from(this.$refs.sortable.children)
                    .map(child => (child.getAttribute('data-sortable-id')))
                this.$dispatch('sorted', data)
            }
        }"
        class="flex flex-col">
        <div x-show="checkboxes.length" class="p-3 flex items-center gap-2 flex-wrap border-b">
            <div class="grow">
                <div class="bg-gray-200 rounded-md inline-flex items-center text-sm">
                    <div class="flex items-center gap-2 px-2 py-1">
                        <x-icon name="check" class="text-gray-400 text-xs"/>
                        <span x-text="checkboxes.length" class="font-medium"></span>
                    </div>

                    <div class="shrink-0 px-2">
                        <x-link label="app.label.select-all" x-on:click="checkboxes = {{ $files->pluck('id')->toJson() }}"/>
                    </div>

                    <div class="shrink-0 px-2">
                        <x-link label="app.label.select-none" x-on:click="checkboxes = []"/>
                    </div>
                </div>
            </div>

            <div class="shrink-0">
                <x-button.confirm color="red" icon="trash" inverted sm
                    x-on:click="$dispatch('confirm', {
                        title: '{{ tr('file.alert.remove.title', 2) }}',
                        message: '{{ tr('file.alert.remove.message', 2) }}',
                        type: 'error',
                        onConfirmed: () => remove(),
                    })"/>
            </div>
        </div>

        @if ($accept === 'image/*')
            <div x-ref="sortable" class="p-4 grid gap-2 grid-cols-2 md:grid-cols-4 lg:grid-cols-6 bg-white max-h-[500px] overflow-auto">
                @foreach ($files as $file)
                    <div class="w-full bg-gray-100 rounded-md overflow-hidden relative" style="padding-top: 100%;">
                        <div class="absolute inset-0 cursor-pointer" x-on:click="() => {
                            checkboxes.length ? select(@js($file->id)) : $wire.emit('updateFile', @js($file->id))
                        }">
                            <img src="{{ $file->url }}" class="w-full h-full object-cover">
                        </div>

                        <div
                            x-on:click.stop="select(@js($file->id))"
                            x-bind:class="checkboxes.includes(@js($file->id)) ? 'inset-0 bg-black/50' : 'top-0 left-0 right-0 h-[40%] bg-gradient-to-b from-black to-transparent'"
                            class="absolute py-1 px-2 cursor-pointer">
                            @if ($multiple)
                                <div x-bind:class="checkboxes.includes(@js($file->id)) ? 'text-green-500' : 'text-white'">
                                    <x-icon name="circle-check"/>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div x-ref="sortable" class="flex flex-col divide-y bg-white max-h-[400px] overflow-auto">
                @foreach ($files as $file)
                    <div class="p-3 flex items-center gap-3" data-sortable-id="{{ $file->id }}">
                        @if ($multiple)
                            <div
                                x-on:click="select(@js($file->id))"
                                x-bind:class="checkboxes.includes(@js($file->id))
                                    ? 'bg-theme ring-1 ring-offset-1 ring-theme'
                                    : 'border border-gray-300 bg-white'" 
                                class="w-5 h-5 rounded text-sm text-white shrink-0 cursor-pointer flex">
                                <x-icon name="check" class="m-auto"/>
                            </div>
                        @endif

                        <figure class="shrink-0 w-6 h-6 rounded-md bg-white border flex items-center justify-center overflow-hidden">
                            @if ($file->is_image) <img src="{{ $file->url }}" class="w-full h-full object-cover">
                            @else <x-icon name="file" class="text-gray-400 text-sm"/>
                            @endif
                        </figure>

                        <div class="grow truncate cursor-pointer font-medium"
                            wire:click="$emit('updateFile', {{ $file->id }})">
                            {{ $file->name }}
                        </div>

                        @if ($multiple)
                            <div class="shrink-0" x-on:click.stop="$dispatch('confirm', {
                                title: '{{ tr('file.alert.remove.title') }}',
                                message: '{{ tr('file.alert.remove.message') }}',
                                type: 'error',
                                onConfirmed: () => remove({{ $file->id }}),    
                            })">
                                <x-close color="red"/>
                            </div>
                        @else
                            <div class="shrink-0" x-on:click.stop="remove()">
                                <x-close color="red"/>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif