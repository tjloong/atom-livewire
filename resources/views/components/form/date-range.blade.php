<div 
    x-data="formDateRange(@js([
        'uid' => $uid,
        'model' => $attributes->wire('model')->value(),
        'value' => $attributes->get('value'),
    ]))" 
    x-on:click.away="close()"
    class="relative"
>
    <div 
        x-ref="input" 
        x-on:updated="$dispatch('input', $event.detail)" 
        {{ $attributes->whereStartsWith('wire') }}>
    </div>

    <div 
        x-ref="anchor" 
        x-bind:class="show && 'active'"
        class="form-input w-full"
    >
        <div class="flex items-center gap-2">
            <x-icon name="calendar" class="text-gray-400"/>

            <a x-on:click="open()" class="grow text-gray-900">
                <div class="flex flex-wrap items-center gap-2">
                    <div x-text="from || '{{ __('From') }}'" class="p-1 rounded"></div>
                    <x-icon name="right-arrow-alt"/>
                    <div x-text="to || '{{ __('To') }}'" class="p-1 rounded"></div>
                    <x-icon name="chevron-down"/>
                </div>
            </a>
        </div>
    </div>

    <div
        x-ref="dd"
        x-show="show"
        x-transition.opacity
        {{ $attributes->class(['absolute bg-white border shadow-lg rounded-md'])->only('class') }}
    >
        <div x-ref="calendar"></div>
    </div>
</div>
