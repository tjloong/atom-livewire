<div class="flex flex-col items-center justify-center">
    <div class="flex items-center gap-4">
        @if ($status === 'success') <x-icon name="circle-check" size="40" class="text-green-500"/>
        @elseif ($status === 'failed') <x-icon name="circle-xmark" size="40" class="text-red-500"/>
        @endif

        <div class="text-4xl font-bold">
            {{ __('Payment '.str($status)->title()->toString()) }}
        </div>
    </div>

    <x-button inverted href="/" label="Back to Home"/>
</div>