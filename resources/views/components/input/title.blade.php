<div x-data="{ value: $wire.get('{{ $attributes->wire('model')->value() }}') }" class="relative mb-6">
    <span x-show="!value" class="absolute text-3xl font-bold text-gray-400 pointer-events-none">
        {{ $slot }}
    </span>
    
    <input
        x-model="value"
        type="text"
        class="w-full bg-transparent appearance-none border-0 p-0 text-3xl font-bold focus:ring-0"
        autofocus
        {{ $attributes }}
    >
    @if ($attributes->has('error'))
        <div class="font-medium text-red-500 mt-2">{{ $attributes->get('error') }}</div>
    @endif
</div>
