<div class="grid gap-6 {{ $this->total['amount'] <= 0 ? 'max-w-screen-sm mx-auto' : '' }}">
    <h1 class="text-xl font-bold">{{ __('Order Summary') }}</h1>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="{{ $this->total['amount'] <= 0 ? 'md:col-span-12' : 'md:col-span-7' }}">
            <div class="grid gap-6">
                <x-box>
                    <div class="grid divide-y">
                        @foreach ($cart as $item)
                            <div class="p-4 flex flex-wrap justify-between gap-4">
                                <div>
                                    <span class="font-bold">{{ $item['name'] }}</span>
                                    @if ($item['recurring'] && $item['recurring'] !== 'lifetime')
                                        <span class="font-medium text-gray-500">({{ str($item['recurring'])->headline() }})</span>
                                    @endif
                                </div>
    
                                <div class="flex-shrink-0 text-right">
                                    @if ($item['amount'] !== $item['grand_total'])
                                        <div class="text-gray-500 line-through font-medium">
                                            {{ currency($item['amount'], $item['currency']) }}
                                        </div>
    
                                        @if ($item['trial'])
                                            <span class="text-sm text-gray-500">
                                                ({{ $item['trial'] }} {{ __('days trial') }})
                                            </span>
                                        @elseif ($item['discounted_amount'])
                                            <span class="text-sm text-red-500 font-medium">
                                                {{ __('Discounted') }} {{ currency($item['discounted_amount']) }}
                                            </span>
                                        @endif

                                        {{ currency($item['grand_total'], $item['currency']) }}
                                    @else
                                        {{ currency($item['grand_total'], $item['currency']) }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <x-slot name="buttons">
                        <div class="flex items-center justify-between gap-4">
                            <div class="text-lg font-bold">{{ __('Total') }}</div>
                            <div class="text-lg font-bold">
                                {{ currency($this->total['amount'], $this->total['currency']) }}
                            </div>
                        </div>
                    </x-slot>
                </x-box>
            
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('billing') }}" class="text-gray-500 font-medium flex items-center gap-1">
                        <x-icon name="left-arrow-alt" size="xs"/> {{ __('Back to Plans') }}
                    </a>

                    @if ($this->total['amount'] <= 0)
                        <x-button icon="check" wire:click="submit">
                            Checkout
                        </x-button>
                    @endif
                </div>
            </div>
        </div>

        @if ($this->total['amount'] > 0)
            <div class="md:col-span-5">
                <x-box>
                    <x-slot:header>{{ __('Payment Method') }}</x-slot:header>
                    <x-payment-gateway callback="submit">
                        <div class="grid gap-4">
                            <x-input.checkbox wire:model="accountOrder.agree_tnc">
                                <div class="grid text-gray-500">
                                    <div>{{ __('I have read and agreed to the app\'s terms & conditions and privacy policy.') }}</div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('page', ['terms']) }}" target="_blank">{{ __('Terms & Conditions') }}</a> | 
                                        <a href="{{ route('page', ['privacy']) }}" target="_blank">{{ __('Privacy Policy') }}</a>
                                    </div>
                                </div>
                            </x-input.checkbox>
    
                            @if ($errors->any())
                                <x-alert :errors="$errors->all()"/>
                            @endif
                        </div>
                    </x-payment-gateway>
                </x-box>
            </div>
        @endif
    </div>
</div>