@if ($attributes->get('back'))
    @php
        $icon = $attributes->get('icon', 'back');
        $label = $attributes->get('label', 'app.label.back');
    @endphp

    <button
        type="button" 
        {{ $attributes->merge([
            'x-on:click' => 'close()',
            'class' => 'bg-gray-200 w-max rounded-full cursor-pointer text-sm py-1 px-3 font-medium flex items-center gap-2 hover:ring-1 hover:ring-offset-2 hover:ring-gray-200',
        ])->except('back', 'label', 'icon') }}>
        @if ($icon) <x-icon :name="$icon"/> @endif
        {!! tr($label) !!}
    </button>
@elseif ($attributes->get('top'))
    <div class="flex items-center gap-4 flex-wrap mb-5">
        <div class="grow">
            @isset ($back)
                {{ $back }}
            @else
                <x-page back/>
            @endif
        </div>

        @if ($slot->isNotEmpty())
            <div class="shrink-0 flex items-center flex-wrap gap-2">
                {{ $slot }}
            </div>
        @endif
    </div>
@else
    @php
        $id = $attributes->get('id') ?? $this->getName() ?? $this->id;
    @endphp

    <div
        x-cloak
        x-data="overlay({{ Js::from($id) }})"
        x-show="show"
        x-transition.opacity.duration.200
        x-bind:class="{
            'left-0 lg:left-0': nav === 'hidden',
            'left-0 lg:left-60': !nav || nav === 'lg',
        }"
        class="overlay page fixed z-20 bottom-0 right-0 overflow-auto bg-gray-50"
        {{ $attributes->merge(['id' => $id])->except('class') }}>
        <div {{ $attributes->class(['min-h-full p-5 pb-20'])->only('class') }}">
            {{ $slot }}
        </div>
    </div>
@endif
