<div x-data="titleInput(@js([
    'model' => $attributes->wire('model')->value(),
    'value' => $attributes->get('value'),
]))" class="relative">
    <span x-show="!value" class="absolute text-3xl font-bold text-gray-400 pointer-events-none">
        {{ __($attributes->get('label') ?? 'Title') }}
    </span>
    
    <input
        x-model="value"
        type="text"
        class="w-full bg-transparent appearance-none border-0 p-0 text-3xl font-bold focus:ring-0"
        autofocus
        {{ $attributes }}
    >

    @if ($err = $attributes->get('error'))
        <div class="font-medium text-red-500 mt-2">{{ $err }}</div>
    @endif
</div>
