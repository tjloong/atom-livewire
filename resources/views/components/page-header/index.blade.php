@props([
    'size' => $attributes->get('size'),
    'back' => $attributes->get('back'),
])

<div {{ $attributes->class([
    'w-full flex flex-wrap justify-between md:flex-nowrap',
    $size === 'sm' ? 'mb-4' : 'mb-6',
])->except(['size', 'back', 'title', 'subtitle', 'status', 'tinylink']) }}>
    <div class="grow flex my-1">
        <div class="shrink-0">
            @if ($href = request()->query('back'))
                <x-page-header.back :size="$size" :href="$href"/>
            @elseif ($back === true || $attributes->wire('back')->value())
                <div x-data="{
                    back () {
                        if (this.$el.closest('.page-overlay')) {
                            this.$dispatch('close')
                            this.$dispatch('back')
                        }
                        else {
                            const href = Array.from(document.querySelectorAll('#breadcrumbs li a'))
                                .map(a => a.getAttribute('href'))
                                .pop()
                            
                            if (!empty(href)) window.location = href
                            else history.back()
                        }
                    },
                }">
                    <x-page-header.back :size="$size" x-on:click.prevent="back"/>
                </div>
            @elseif ($back)
                <x-page-header.back :size="$size" :href="$back"/>
            @endif
        </div>

        @isset($title)
            <div {{ $title->attributes->merge(['class' => 'self-center']) }}>
                {{ $title }}
            </div>
        @elseif ($title = $attributes->get('title'))
            <div class="self-center grid">
                <div class="text-gray-800 font-bold truncate {{ 
                    $size === 'sm' 
                        ? 'text-lg font-bold' 
                        : 'text-2xl font-bold' 
                }}">
                    {{ str(__($title))->toHtmlString() }}
                </div>

                @isset($subtitle)
                    <div class="text-gray-600 font-light font-medium">{{ $subtitle }}</div>
                @elseif($subtitle = $attributes->get('subtitle'))
                    <div class="text-gray-600 font-light font-medium {{
                        $size === 'sm'
                            ? 'text-sm'
                            : ''
                    }}">
                        {{ str(__($subtitle))->toHtmlString() }}
                    </div>
                @endif
            </div>
        @endif

        @if ($status = $attributes->get('status'))
            <div class="shrink-0 px-2">
                @if (is_string($status)) <x-badge :label="$status"/>
                @else
                    @foreach ($status as $color => $val)
                        <x-badge :label="$val" :color="$color"/>
                    @endforeach
                @endif
            </div>
        @endif

        @if ($tinylink = $attributes->get('tinylink'))
            <div class="shrink-0 px-2">
                <a 
                    href="{{ data_get($tinylink, 'href') }}"
                    x-tooltip:right="{{ data_get($tinylink, 'label') }}"
                    class="text-gray-400 flex items-center gap-1 py-1" 
                >
                    <x-icon :name="data_get($tinylink, 'icon', 'gear')"/>
                </a>
            </div>
        @endif
    </div>

    <div class="shrink-0 self-center my-1">
        {{ $slot }}
    </div>
</div>
