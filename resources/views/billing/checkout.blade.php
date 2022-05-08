<div class="grid gap-6 {{ data_get($this->total, 'amount') <= 0 ? 'max-w-screen-sm mx-auto' : '' }}">
    <h1 class="text-xl font-bold">{{ __('Order Summary') }}</h1>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="{{ data_get($this->total, 'amount') <= 0 ? 'md:col-span-12' : 'md:col-span-7' }}">
            <div class="grid gap-6">
                <x-box>
                    <div class="grid divide-y">
                        @foreach ($cart as $item)
                            <div class="p-4 flex flex-wrap justify-between gap-4">
                                <div>
                                    <span class="font-bold">
                                        {{ data_get($item, 'name') }}
                                    </span>

                                    @if (data_get($item, 'recurring') !== 'lifetime')
                                        <span class="font-medium text-gray-500">
                                            ({{ str(data_get($item, 'recurring'))->headline() }})
                                        </span>
                                    @endif
                                </div>
    
                                <div class="flex-shrink-0 text-right">
                                    @if (data_get($item, 'amount') !== data_get($item, 'grand_total'))
                                        <div class="text-gray-500 line-through font-medium">
                                            {{ currency(data_get($item, 'amount'), data_get($item, 'currency')) }}
                                        </div>
    
                                        @if ($trial = data_get($item, 'trial'))
                                            <span class="text-sm text-gray-500">
                                                ({{ $trial }} {{ __('days trial') }})
                                            </span>
                                        @elseif ($discount = data_get($item, 'discounted_amount'))
                                            <span class="text-sm text-red-500 font-medium">
                                                {{ __('Discounted') }} {{ currency($discount) }}
                                            </span>
                                        @endif

                                        {{ currency(data_get($item, 'grand_total'), data_get($item, 'currency')) }}
                                    @else
                                        {{ currency(data_get($item, 'grand_total'), data_get($item, 'currency')) }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <x-slot name="buttons">
                        <div class="grid gap-4">
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-lg font-bold">{{ __('Total') }}</div>
                                <div class="text-lg font-bold">
                                    {{ currency(data_get($this->total, 'amount'), data_get($this->total, 'currency')) }}
                                </div>
                            </div>
    
                            @if (data_get($this->total, 'amount') <= 0)
                                <div class="grid gap-4">
                                    <x-form.agree tnc wire:model="accountOrder.agree_tnc"/>
        
                                    @if ($errors->any())
                                        <x-alert :errors="$errors->all()"/>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </x-slot>
                </x-box>
            
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('billing') }}" class="text-gray-500 font-medium flex items-center gap-1">
                        <x-icon name="left-arrow-alt" size="xs"/> {{ __('Back to Plans') }}
                    </a>

                    @if (data_get($this->total, 'amount') <= 0)
                        <x-button icon="check" wire:click="submit">
                            Checkout
                        </x-button>
                    @endif
                </div>
            </div>
        </div>

        @if (data_get($this->total, 'amount') > 0)
            <div class="md:col-span-5">
                <x-box>
                    <x-slot:header>{{ __('Payment Method') }}</x-slot:header>
                    <x-payment-gateway callback="submit">
                        <div class="grid gap-4">
                            <x-form.agree tnc wire:model="accountOrder.agree_tnc"/>
    
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