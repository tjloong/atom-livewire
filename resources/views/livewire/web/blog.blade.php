<main class="min-h-screen">    
    <div 
        x-data="{
            text: @js($search),
            labels: @js(explode(',', $labels)),
            search () {
                const params = new URLSearchParams({
                    search: this.text || '',
                    labels: this.labels,
                })

                window.location = '/blog?'+params.toString()
            },
        }"
        class="max-w-screen-xl mx-auto grid gap-12 px-4 py-14"
    >
        <div class="flex flex-wrap items-center gap-4 justify-between">
            @if ($this->blog)
                <a href="{{ route('web.blog') }}" class="flex items-center gap-3 text-gray-500">
                    <x-icon name="arrow-left"/> {{ __('Back') }}
                </a>
            @else
                <h1 class="text-5xl font-extrabold flex-shrink-0">
                    {{ $this->title ?? $title ?? 'Blogs' }}
                </h1>
            @endif

            <div class="max-w-md">
                <form x-on:submit.prevent="search">
                    <x-form.text 
                        x-model="text"
                        placeholder="Search Articles"
                        prefix="icon:search"
                    >
                        @if ($search)
                            <x-slot:button icon="close" x-on:click="text = ''; search()"></x-slot:button>
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
                                    :href="'/blog/'.$blog->slug"
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

                            <div 
                                x-data="{
                                    toggle (slug) {
                                        const index = this.labels.findIndex(label => label === slug)
                                        if (index > -1) this.labels.splice(index, 1)
                                        else this.labels.push(slug)

                                        search()
                                    },
                                }"
                                class="flex items-center flex-wrap gap-2"
                            >
                                @foreach ($this->topics as $label)
                                    <div 
                                        x-data="{ 
                                            get isActive () { return labels.includes(@js($label->slug)) },
                                        }"
                                        x-on:click="toggle(@js($label->slug))"
                                        x-bind:class="{
                                            'border-theme bg-theme text-white': isActive,
                                            'border-gray-400 bg-white text-gray-500': !isActive,
                                        }"
                                        class="inline-block py-1.5 px-3 rounded-lg font-medium border-2 cursor-pointer"
                                    >
                                        <div class="flex items-center gap-2">
                                            {{ $label->locale('name') }}
                                            <div x-show="isActive" class="flex">
                                                <x-icon name="xmark" class="m-auto"/>
                                            </div>
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
                                    :href="'/blog/'.$val->slug"
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
                                    :href="'/blog/'.$val->slug"
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
