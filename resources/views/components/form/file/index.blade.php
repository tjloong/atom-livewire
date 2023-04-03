@props([
    'id' => component_id($attributes, 'file-input'),
    'multiple' => $attributes->get('multiple', false),
    'model' => $attributes->wire('model')->value(),
    'value' => data_get($this, $attributes->wire('model')->value()),
    'tabs' => array_filter([
        $attributes->get('upload', true) ? 'upload' : null,

        $attributes->get('web-image', true) 
        || $attributes->get('youtube', false)
        || str($attributes->get('accept'))->is('*image*')
            ? 'url' : null,
        
        $attributes->get('library', true) ? 'library' : null,
    ]),
])

<x-form.field {{ $attributes }}>
    <div class="flex flex-col gap-4" {{ $attributes->wire('file') }}>
        @if ($slot->isNotEmpty()) {{ $slot }}
        @elseif ($value)
            <div id="file-preview" class="flex items-center gap-3 flex-wrap">
                @foreach ((array)$value as $val)
                    <x-thumbnail :file="$val" downloadable
                        wire:remove="$set('{{ $model }}', {{ json_encode(
                            $multiple
                                ? collect($value)->reject($val)->values()->all()
                                : collect($value)->reject($val)->first()
                        ) }})"
                    />
                @endforeach
            </div>
        @endif

        <div
            x-data="{ 
                tab: @js(head($tabs)),
                model: @js($model),
                multiple: @js($multiple),
                get disabled () {
                    if (this.model) return !this.multiple && !empty(this.$wire.get(this.model))
                    return false
                },
                switchtab (name) {
                    if (name === 'library') {
                        $el.querySelector('#file-library').dispatchEvent(new Event('open'))
                    }
                    else this.tab = name
                },
                input (e) {
                    if (empty(e.detail)) return
                    
                    const model = @js($attributes->wire('model')->value());
                    const files = [e.detail].flat().map(file => (file.id))

                    if (model) {
                        if (this.multiple) {
                            const value = (this.$wire.get(model) || []).concat(files)
                            const unique = [...new Set(value)]
                            this.$wire.set(model, unique)
                        }
                        else this.$wire.set(model, files[0])
                    }
                    else if (this.multiple) this.$dispatch('file', files)
                    else this.$dispatch('file', files[0])
                },
            }"
            x-show="!disabled"
            x-on:uploaded="input"
            x-on:url="input"
            x-on:library="input"
            class="flex flex-col gap-2"
        >
            @if (count($tabs) > 1)
                <div class="flex items-center gap-2">
                    @foreach ($tabs as $tab)
                        <div 
                            x-on:click="switchtab(@js($tab))"
                            x-bind:class="tab === @js($tab)
                                ? 'bg-gray-100 font-semibold shadow border-gray-300 text-gray-600'
                                : 'bg-white font-medium text-gray-400 cursor-pointer'"
                            class="text-xs py-1 px-2 border rounded-lg flex items-center gap-1"
                        >
                            <x-icon :name="[
                                'upload' => 'upload',
                                'url' => 'at',
                                'library' => 'grip',
                            ][$tab]" size="12"/> 
                            {{ __(str($tab)->upper()->toString()) }}
                        </div>
                    @endforeach
                </div>
            @endif

            @if (in_array('upload', $tabs))
                <div x-show="tab === 'upload'">
                    <x-form.file.dropzone 
                        :accept="$attributes->get('accept')"
                        :visibility="$attributes->get('visibility')"
                        :multiple="$multiple"
                    />
                </div>
            @endif

            @if (in_array('url', $tabs))
                <div x-show="tab === 'url'">
                    <x-form.file.url 
                        :multiple="$multiple"
                        :youtube="$attributes->get('youtube')"
                        :web-image="$attributes->get('web-image', true) ?? str($attributes->get('accept'))->is('*image*')"
                    />
                </div>
            @endif

            @if (in_array('library', $tabs))
                <x-form.file.library 
                    :header="$attributes->get('header')"
                    :multiple="$multiple"
                    :filters="[
                        'type' => [
                            'image/*' => 'image',
                            'video/*' => 'video',
                            'audio/*' => 'audio',
                            'youtube' => 'youtube',
                        ][$attributes->get('accept')] ?? null,
                    ]"
                />
            @endif
        </div>
    </div>
</x-form.field>
