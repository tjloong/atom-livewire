<div class="min-h-screen bg-gray-100">
    <div class="max-w-screen-lg mx-auto px-5 py-14">
        @if ($slug)
            @livewire(lw('web.thank.'.$slug), $params)
        @else
            <div class="flex flex-col items-center justify-center">
                <div class="text-4xl font-bold text-center">
                    {{ __('Thank You') }}
                </div>
                <x-button inverted href="/" label="Back to Home"/>
            </div>
        @endif
    </div>
</div>