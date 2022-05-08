<form {{ $attributes->merge([
    'class' => 'bg-white shadow rounded-lg border',
    'wire:submit.prevent' => 'submit',
]) }}>
    <div class="p-1">
        @if ($header = $attributes->get('header'))
            <div class="py-3 px-4 text-lg font-bold border-b">
                {{ __($header) }}
            </div>
        @elseif (isset($header))
            {{ $header }}
        @endif

        <div class="p-5 grid gap-6">
            {{ $slot }}
        </div>
    </div>

    @if (isset($foot) && $foot->isNotEmpty())
        <div class="py-4 px-6 bg-gray-100 rounded-b-lg">
            {{ $foot }}
        </div>
    @endif
</form>
