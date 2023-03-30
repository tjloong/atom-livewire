<main class="min-h-screen">    
    <div class="max-w-screen-xl mx-auto grid gap-12 px-4 py-14">
        <div class="flex flex-wrap items-center gap-4 justify-between">
            @if ($this->blog)
                <x-link :href="route('web.blog')" label="Back" icon="arrow-left" class="flex items-center gap-3 text-gray-500"/>
            @else
                <h1 class="text-5xl font-extrabold flex-shrink-0">
                    {{ $this->title ?? $title ?? 'Blogs' }}
                </h1>
            @endif

            <div class="max-w-md">
                <form wire:submit.prevent="$emit('refresh')">
                    <x-form.text wire:model.defer="filters.search" placeholder="Search Articles" prefix="icon:search" :label="false">
                        @if (!empty(data_get($filters, 'search')))
                            <x-slot:button icon="close" wire:click="$set('filters.search', null)"></x-slot:button>
                        @endif
                    </x-form.text>
                </form>
            </div>
        </div>

        <div class="flex flex-col gap-6 md:flex-row">
            <div class="{{ $this->sidebar ? 'md:w-9/12' : 'w-full' }}">
                @if ($this->blog)
                    <x-blog
                        :title="$this->blog->title"
                        :posted-at="$this->blog->published_at"
                        :cover="$this->blog->cover"
                    >
                        {!! $this->blog->content !!}
                    </x-blog>
                @else
                    <div class="grid gap-10">
                        <div class="grid gap-6 md:grid-cols-3">
                            @forelse ($this->blogs as $blog)
                                <x-blog.card
                                    :href="route('web.blog', [$blog->slug])"
                                    :cover="optional($blog->cover)->url"
                                    :title="$blog->title"
                                    :excerpt="html_excerpt($blog->excerpt ?? $blog->content)"
                                />
                            @empty
                                <div class="md:col-span-3">
                                    <x-empty-state icon="news" title="No articles found" subtitle=""/>
                                </div>
                            @endforelse
                        </div>
        
                        {{ $this->blogs->links() }}
                    </div>
                @endif
            </div>

            @if ($this->sidebar)
                <div class="md:w-3/12 flex flex-col gap-10">
                    @if ($topics = data_get($this->sidebar, 'topics'))
                        <div class="grid gap-4">
                            <div class="text-sm font-semibold text-gray-400 uppercase">
                                Topics
                            </div>

                            <div class="flex items-center flex-wrap gap-2">
                                @foreach ($this->topics as $label)
                                    @php $active = in_array($label->slug, data_get($filters, 'labels', [])) @endphp
                                    <div class="inline-block py-1.5 px-3 rounded-lg font-medium border-2 cursor-pointer {{ 
                                        $active ? 'border-theme bg-theme text-white' : 'border-gray-400 bg-white text-gray-500'
                                    }}">
                                        <div class="flex items-center gap-2">
                                            <div wire:click="$set('filters.labels', @js(
                                                collect(data_get($filters, 'labels'))->concat([$label->slug])->unique()->toArray()
                                            ))">
                                                {{ $label->locale('name') }}
                                            </div>

                                            @if ($active)
                                                <x-close wire:click="$set('filters.labels', {{
                                                    collect(data_get($filters, 'labels'))->reject($label->slug)->unique()->toJson()
                                                }})" class="text-white hover:text-gray-500"/>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($related = data_get($this->sidebar, 'related'))
                        <div class="grid gap-4">
                            <div class="text-sm font-semibold text-gray-400 uppercase">
                                {{ __('Related Articles') }}
                            </div>

                            @foreach ($related as $val)
                                <x-blog.card size="sm"
                                    :href="route('web.blog', [$val->slug])"
                                    :title="$val->title"
                                    :excerpt="html_excerpt($val->excerpt ?? $val->content)"
                                    :date="$val->published_at"
                                />
                            @endforeach
                        </div>
                    @endif

                    @if ($recents = data_get($this->sidebar, 'recents'))
                        <div class="grid gap-4">
                            <div class="text-sm font-semibold text-gray-400 uppercase">
                                {{ __('More Articles') }}
                            </div>

                            @foreach ($recents as $val)
                                <x-blog.card size="sm"
                                    :href="route('web.blog', [$val->slug])"
                                    :title="$val->title"
                                    :excerpt="html_excerpt($val->excerpt ?? $val->content)"
                                    :date="$val->published_at"
                                />
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</main>
