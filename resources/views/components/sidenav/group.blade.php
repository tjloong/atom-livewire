<label class="block text-sm text-gray-400 font-medium uppercase mt-4 mb-2 first-of-type:mt-0">
    @if ($label = $attributes->get('label')) {{ tr($label) }}
    @else {{ $slot }}
    @endif
</label>
