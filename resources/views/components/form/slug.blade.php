@php
    $url = $attributes->get('url');
    $prefix = $attributes->get('prefix', '/');
    $except = ['error', 'required', 'caption', 'prefix'];
@endphp

<x-form.field {{ 
    $attributes->except('caption')
}} :caption="$attributes->get('caption', 'Leave empty to auto generate')">
    <div
        x-data="{ active: false }"
        x-bind:class="active && 'active'"
        class="flex items-center form-input w-full">
        @if ($prefix)
            <div class="bg-gray-100 rounded-l-md -my-1.5 -ml-3 py-1.5 px-3 border-r border-gray-300 text-gray-400">
                {{ $prefix }}
            </div>
        @endif

        <input
            x-on:focus="active = true"
            x-on:blur="active = false"
            type="text"
            class="p-0 border-0 w-full appearance-none px-3 focus:ring-0"
            {{ $attributes->except($except) }}>

        @if ($url)
            <button type="button" class="shrink-0 flex items-center gap-1 px-3 -mx-3 py-1.5 -my-1.5 text-sm bg-white border-l hover:bg-gray-100"
                x-on:click.prevent="navigator.clipboard.writeText(@js($url)).then(() => $dispatch('toast', { message: @js(tr('app.label.url-copied')) }))">
                <div class="shrink-0 flex">
                    <x-icon name="copy" class="m-auto"/>
                </div>
                {!! tr('app.label.copy-link') !!}
            </button>
        @endif
    </div>
</x-form.field>
