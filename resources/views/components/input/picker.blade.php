<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    @if ($slot->isNotEmpty())
        <x-slot name="label">{{ $slot }}</x-slot>
    @endif

    <div x-data="pickerInput('{{ $getter }}', '{{ $labelKey }}')">
        @isset($trigger)
            <div x-on:click.prevent="open()">
                {{ $trigger }}
            </div>
        @else
            <a class="inline-flex items-center gap-2" x-on:click.prevent="open()">
                {{ $attributes->get('placeholder') ?? 'Select Option'}} <x-icon name="chevron-down"/>
            </a>
        @endif

        <div x-show="show" x-transition.opacity class="modal">
            <div class="modal-bg"></div>
            <div class="modal-container" x-on:click="close()">
                <div 
                    x-on:click.stop
                    {{ $attributes->class([
                        'modal-content',
                        'w-80' => !$attributes->get('class'),
                    ])->whereStartsWith('class') }}
                >
                    <div class="px-4 pt-4 pb-2 flex items-center justify-between">
                        <div class="font-semibold text-lg">
                            @isset($title) {{ $title }}
                            @else Please Select
                            @endisset
                        </div>
    
                        <a class="text-gray-500 flex items-center justify-center" x-on:click.prevent="close()">
                            <x-icon name="x"/>
                        </a>
                    </div>
    
                    <div class="p-4">
                        <div class="bg-gray-100 rounded-md py-2 px-3 drop-shadow flex items-center space-x-2">
                            <x-icon name="search" class="flex-shrink-0 text-gray-400" size="18px"/>
                            <input
                                x-ref="text"
                                x-model="text"
                                x-on:input.debounce="fetch()"
                                type="text"
                                class="text-sm leading-tight w-full appearance-none bg-transparent p-0 border-0 focus:ring-0"
                                placeholder="Search"
                            >
                            <template x-if="text">
                                <a x-on:click.prevent="text = null; fetch()" class="flex-shrink-0 flex items-center justify-center text-gray-500">
                                    <x-icon name="x" size="18px"/>
                                </a>
                            </template>
                        </div>
                    </div>
    
                    <div {{ $attributes->whereStartsWith('wire') }}>
                        <template x-if="options.length">
                            <div>
                                <div class="mb-4">
                                    <template x-for="opt in options" x-bind:key="opt.id">
                                        <div x-on:click="pick(opt)" class="cursor-pointer py-2 px-4 border-t text-sm hover:bg-gray-100">
                                            @isset($item) {{ $item }}
                                            @else <span class="font-medium" x-text="opt[labelKey]"></span>
                                            @endif
                                        </div>
                                    </template>
                                </div>
        
                                <template x-if="loading">
                                    <div class="p-4 bg-gray-100 flex items-center justify-center">
                                        <x-loader size="20px" class="text-gray-500"/>
                                        <div class="font-medium">
                                            Loading
                                        </div>
                                    </div>
                                </template>
        
                                <template x-if="!loading && paginator?.current_page < paginator?.last_page">
                                    <div class="p-4 px-4 bg-gray-100">
                                        <a 
                                            class="w-full py-1.5 flex items-center justify-center border border-gray-300 rounded-md bg-white text-gray-900"
                                            x-on:click.prevent="fetch(paginator.current_page + 1)"
                                        >
                                            Load More
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </template>
        
                        <template x-if="!options.length && loading">
                            <div class="flex items-center justify-center py-8">
                                <x-loader size="32px"/>
                            </div>
                        </template>
        
                        <template x-if="!options.length && !loading">
                            <x-empty-state/>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pickerInput', (getter, labelKey) => ({
                labelKey,
                show: false,
                text: null,
                loading: true,
                options: [],
                paginator: null,
    
                fetch (page = 1) {
                    this.loading = true
    
                    this.$wire[getter](page, this.text).then(res => {
                        this.paginator = res
                        this.options = page === 1 ? res.data : this.options.concat(res.data)
                        this.loading = false
                    })
                },
    
                pick (opt) {
                    this.selected = opt
                    this.$dispatch('input', opt.id)
                    this.close()
                },
    
                open () {
                    document.documentElement.classList.add('overflow-hidden')
                    this.show = true
                    this.fetch()
                },
                
                close () {
                    document.documentElement.classList.remove('overflow-hidden')
                    this.show = false
                    this.text = null
                },
            }))
        })
    </script>
</x-input.field>
