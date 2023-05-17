<x-form.field {{ 
    $attributes->except('caption')
}} :caption="$attributes->get('caption', 'Leave empty to auto generate')">
    <div
        x-data="{ active: false }"
        x-bind:class="active && 'active'"
        class="flex items-center form-input w-full {{ 
            component_error(optional($errors), $attributes) ? 'error' : '' 
        }}"
    >
        @if ($prefix = $attributes->get('prefix', '/'))
            <div class="bg-gray-100 rounded-l-md -my-1.5 -ml-3 py-1.5 px-3 border-r border-gray-300 text-gray-400">
                {{ $prefix }}
            </div>
        @endif

        <input
            x-on:focus="active = true"
            x-on:blur="active = false"
            type="text"
            class="p-0 border-0 w-full appearance-none px-3 focus:ring-0"
            {{ $attributes->except(['error', 'required', 'caption', 'prefix']) }}
        >

        @if (isset($button))
            @php $label = $button->attributes->get('label') @endphp 
            @php $icon = $button->attributes->get('icon') @endphp 
            <a {{ $button->attributes->class([
                'flex items-center justify-center gap-2 rounded-full -mr-1 text-sm',
                $label ? 'px-2 py-0.5' : null,
                !$label && $icon ? 'p-1' : null,
                $button->attributes->get('class', 'text-gray-800 bg-gray-200'),
            ]) }}">
                @if ($icon) <x-icon :name="$icon" size="11"/> @endif
                {{ __($label) }}
            </a>
        @elseif ($url = $attributes->get('url'))
            <a 
                x-on:click.prevent="navigator.clipboard.writeText(@js($url)).then(() => $dispatch('toast', { message: @js(__('URL copied.')) }))"
                class="shrink-0 flex items-center justify-center gap-2 rounded-full -mr-1 text-sm px-2 py-0.5 text-gray-800 bg-gray-200"
            >
                <x-icon name="copy" size="11"/> {{ __('Copy Link') }}
            </a>
        @endif
    </div>
</x-form.field>
