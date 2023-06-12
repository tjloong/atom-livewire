<div class="flex flex-col gap-1">
    @if (is_numeric($attributes->get('total')))
        <div class="grid grid-cols-2 text-lg font-bold">
            <div class="md:text-right">{{ __('TOTAL') }}</div>
            <div class="text-right">{{ $attributes->get('total') }}</div>    
        </div>
    @else
        @foreach ($attributes->get('total') as $key => $val)
            @if ($key === 'Grand Total')
                <div class="grid grid-cols-2 text-lg font-bold">
                    <div class="md:text-right">{{ __(str()->upper($key)) }}</div>
                    <div class="text-right">{{ $val }}</div>    
                </div>
            @else
                <div class="grid grid-cols-2 font-medium text-gray-500">
                    <div class="md:text-right">{{ __(str()->upper($key)) }}</div>
                    <div class="text-right">{{ $val }}</div>
                </div>
            @endif
        @endforeach
    @endif
</div>