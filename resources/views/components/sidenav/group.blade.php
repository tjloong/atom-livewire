<label class="block text-sm text-gray-400 font-medium uppercase mt-4 first-of-type:mt-0">
    @if ($label = $attributes->get('label')) {{ __($label) }}
    @else {{ $slot }}
    @endif
</label>
