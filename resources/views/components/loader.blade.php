@if ($fullscreen)
    <div class="fixed inset-0 z-30">
        <div class="absolute w-full h-full bg-white opacity-50"></div>
        <div class="absolute right-0 bottom-0 p-6 text-theme">
            <box-icon 
                name="radio-circle" 
                animation="burst"
                color="currentcolor"
                size="64px"
            >
            </box-icon>
        </div>
    </div>
@else
    <svg
        {{ $attributes->class([
            'loader animate-spin -ml-1 mr-3',
            'text-theme' => !$attributes->get('class'),
        ]) }}
        xmlns="http://www.w3.org/2000/svg" 
        fill="none" 
        viewBox="0 0 24 24"
        style="width: {{ $size }}; height: {{ $size }}"
    >
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
@endif
