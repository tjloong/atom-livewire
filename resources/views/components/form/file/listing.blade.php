@php
    $accept = $attributes->get('accept');
    $multiple = $attributes->get('multiple');
    $sortable = $attributes->get('sortable');
    $model = $attributes->wire('model')->value();
    $value = data_get($this, $model);
    $files = $value ? collect($value)->map(fn($id) => model('file')->find($id))->filter() : null;
@endphp

@if ($files && $files->count())
    <div wire:key="file-input-listing"
        x-data="{
            cols: 4,
            value: @entangle($model),
            multiple: @js($multiple),
            sortable: @js($sortable),
            checkboxes: [],
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
                const data = Array.from(this.$refs.sortable.children).map(child => (child.getAttribute('data-sortable-id')))
                this.$dispatch('sorted', data)
            },
            isSelected (id) {
                return this.checkboxes.includes(id)
            },
        }"
        x-init="() => {
            if (sortable) new Sortable($refs.sortable, { onSort: () => sorted() })

            $nextTick(() => {
                let w = $el.offsetWidth
                w = w > 800 ? 800 : w
                if (w > 300 && w <= 800) cols = Math.round(w/100) + 2
            })
        }"
        class="flex flex-col">
        <div x-show="checkboxes.length" class="p-3 flex items-center gap-2 flex-wrap border-b">
            <div class="grow">
                <div class="py-1 px-3 bg-gray-100 border rounded-md inline-flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="shrink-0 text-gray-400">
                            <x-icon name="check-double"/>
                        </div>
    
                        <div class="grow">
                            <span x-text="checkboxes.length"></span> {{ tr('app.label.selected') }}
                        </div>
                    </div>

                    <div class="shrink-0 cursor-pointer text-blue-600 text-xs uppercase" x-on:click="checkboxes = {{ $files->pluck('id')->toJson() }}">
                        {{ tr('app.label.select-all') }}
                    </div>

                    <div class="shrink-0 cursor-pointer text-blue-600 text-xs uppercase" x-on:click="checkboxes = []">
                        {{ tr('app.label.select-none') }}
                    </div>
                </div>
            </div>

            <div class="shrink-0">
                <x-button.confirm color="red" icon="trash" label="app.label.remove" inverted sm
                    x-on:click="$dispatch('confirm', {
                        title: '{{ tr('app.alert.remove-file.title', 2) }}',
                        message: '{{ tr('app.alert.remove-file.message', 2) }}',
                        type: 'error',
                        onConfirmed: () => remove(),
                    })"/>
            </div>
        </div>

        @if ($accept === 'image/*')
            @if ($multiple)
                <div
                    x-ref="sortable"
                    x-bind:class="{
                        'grid-cols-4': cols === 4,
                        'grid-cols-5': cols === 5,
                        'grid-cols-6': cols === 6,
                        'grid-cols-7': cols === 7,
                        'grid-cols-8': cols === 8,
                        'grid-cols-9': cols === 9,
                        'grid-cols-10': cols === 10,
                    }"
                    class="p-4 grid gap-2 bg-white max-h-[500px] overflow-auto">
                    @foreach ($files as $file)
                        <div 
                            class="w-full bg-gray-100 rounded-md overflow-hidden relative"
                            data-sortable-id="@js($file->id)"
                            style="padding-top: 100%;">
                            <div
                                x-on:click="checkboxes.length ? select(@js($file->id)) : $wire.emit('updateFile', @js($file->id))"
                                class="absolute inset-0 cursor-pointer" >
                                <img src="{{ $file->url }}" class="w-full h-full object-cover">
                            </div>

                            <div
                                x-on:click.stop="select(@js($file->id))"
                                x-bind:class="isSelected(@js($file->id)) ? 'inset-0 bg-black/50' : 'top-0 left-0 right-0 h-[40%] bg-gradient-to-b from-black to-transparent'"
                                class="absolute py-1 px-2 cursor-pointer">
                                <div x-bind:class="isSelected(@js($file->id)) ? 'text-green-500' : 'text-white'">
                                    <x-icon name="circle-check"/>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 bg-white">
                    <div
                        x-on:click.stop="$wire.emit('updateFile', @js($files->first()->id))"
                        class="relative w-28 h-28 rounded-md bg-gray-100 shadow overflow-hidden">
                        <img src="{{ $files->first()->url }}" class="w-full h-full object-cover">

                        <div
                            x-on:click.stop="remove()"
                            class="absolute p-2 cursor-pointer top-0 left-0 right-0 h-[40%] bg-gradient-to-b from-black to-transparent">
                            <div class="w-5 h-5 rounded-full bg-red-500 text-white flex">
                                <x-icon name="xmark" class="m-auto"/>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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