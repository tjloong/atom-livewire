<label 
    x-bind:class="!show && 'hidden md:block'"
    class="block text-sm text-gray-400 font-medium uppercase py-1 px-3 mt-4 first-of-type:mt-0"
>
    @if ($label = $attributes->get('label')) {{ __($label) }}
    @else {{ $slot }}
    @endif
</label>
