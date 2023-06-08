<main class="min-h-screen py-10 px-4">
    <div class="max-w-screen-xl mx-auto flex flex-col gap-10 md:flex-row">
        <div class="md:w-5/12 flex flex-col divide-y">
            <div class="py-4 flex flex-col gap-4">
                <h1 class="text-xl font-bold">{{ __('Order Summary') }}</h1>
                
                <div class="flex flex-col divide-y">
                    @livewire(atom_lw('web.shop.cart.item'), [
                        'readonly' => true,
                        'order' => $order,
                    ], key('item-'.uniqid()))

                    @livewire(atom_lw('web.shop.cart.sum'), compact('order'), key('sum-'.uniqid()))
                </div>
            </div>
        </div>

        <div class="md:w-7/12">
            @if ($step === 'info')
                <form wire:submit.prevent="submit" class="bg-gray-100 rounded-lg p-6 flex flex-col gap-8 md:p-8">
                    <div class="flex flex-col gap-4">
                        <div class="text-lg font-semibold">{{ __('Contact Information') }}</div>
                        <div class="flex flex-col gap-2">
                            <x-form.email wire:model.defer="inputs.email"/>
                            <x-form.checkbox wire:model="inputs.data.agree_email_marketing" label="Email me with news and offers"/>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div class="text-lg font-semibold">{{ __('Shipping Address') }}</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <x-form.select.country wire:model="inputs.shipping_address.country"/>
                            </div>

                            <x-form.text wire:model.defer="inputs.shipping_address.first_name"/>
                            <x-form.text wire:model.defer="inputs.shipping_address.last_name"/>
                            <x-form.text wire:model.defer="inputs.shipping_address.company"/>
                            <x-form.text wire:model.defer="inputs.shipping_address.address_1"/>
                            <x-form.text wire:model.defer="inputs.shipping_address.address_2"/>
                            <x-form.text wire:model.defer="inputs.shipping_address.postcode"/>
                            <x-form.text wire:model.defer="inputs.shipping_address.city"/>
                            <x-form.select.state wire:model.defer="inputs.shipping_address.state" :country="data_get($inputs, 'shipping_address.country')"/>

                            <div class="md:col-span-2 flex flex-col gap-2">
                                <x-form.phone wire:model.defer="inputs.phone"/>
                                <x-form.checkbox wire:model="inputs.data.agree_phone_marketing" label="Text me with news and offers"/>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div class="text-lg font-semibold">{{ __('Billing Address') }}</div>

                        <x-form.checkbox wire:model="inputs.billing_address.same_as_shipping" label="Same as shipping address"/>

                        @if (!data_get($inputs, 'billing_address.same_as_shipping'))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <x-form.select.country wire:model="inputs.billing_address.country"/>
                                </div>

                                <x-form.text wire:model.defer="inputs.billing_address.first_name"/>
                                <x-form.text wire:model.defer="inputs.billing_address.last_name"/>
                                <x-form.text wire:model.defer="inputs.billing_address.company"/>
                                <x-form.text wire:model.defer="inputs.billing_address.address_1"/>
                                <x-form.text wire:model.defer="inputs.billing_address.address_2"/>
                                <x-form.text wire:model.defer="inputs.billing_address.postcode"/>
                                <x-form.text wire:model.defer="inputs.billing_address.city"/>
                                <x-form.select.state wire:model.defer="inputs.billing_address.state" :country="data_get($inputs, 'shipping_address.country')"/>
                                <x-form.phone wire:model.defer="inputs.phone"/>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 md:items-end md:justify-between md:flex-row">
                        <div class="shrink-0">
                            <x-link label="Return to cart" icon="arrow-left" :href="route('web.shop.cart')"/>
                        </div>
                        <div class="shrink-0">
                            <x-button.submit icon="arrow-right"
                                :label="$step === 'info' && $items->where('is_required_shipping', true)->count()
                                    ? 'Continue to shipping' 
                                    : 'Continue to payment'
                                "
                            />
                        </div>
                    </div>
                </form>
            @elseif ($step === 'shipping')
                <form wire:submit.prevent="submit" class="bg-gray-100 rounded-lg p-6 flex flex-col gap-8 md:p-8">
                    <x-box>
                        <div class="flex flex-col divide-y">
                            <x-field label="Contact" :value="$order->email">
                                <x-slot:small>
                                    <x-link label="Change" :href="route('web.shop.checkout')"/>
                                </x-slot:small>
                            </x-field>

                            <x-field label="Ship To" :value="format_address($order->shipping_address)">
                                <x-slot:small>
                                    <x-link label="Change" :href="route('web.shop.checkout')"/>
                                </x-slot:small>
                            </x-field>
                        </div>
                    </x-box>

                    <div class="flex flex-col gap-4">
                        <div class="text-lg font-semibold">{{ __('Shipping Method') }}</div>

                        @if ($this->rates->count())
                            <x-box>
                                <div class="flex flex-col divide-y">
                                    @foreach ($this->rates as $rate)
                                        @php $active = $order->shipping_rate_id === $rate->id @endphp
                                        <div wire:click="setShippingRate(@js($rate->id))" class="py-2 px-4 flex items-center gap-4 cursor-pointer hover:bg-slate-100">
                                            @if ($active) <x-icon name="circle-check" class="text-green-500"/>
                                            @else <x-icon name="circle-minus" class="text-gray-400"/>
                                            @endif

                                            <div class="grow flex flex-col md:flex-row md:items-center">
                                                <div class="grow font-medium {{ $active ? '' : 'text-gray-500' }}">
                                                    {{ $rate->name }}
                                                </div>
                                                <div class="shrink-0 text-gray-500">
                                                    {{ currency($rate->price ?? 0) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </x-box>
                        @else
                            <x-empty-state title="No Shipping Methods" subtitle="No shipping rates found for this address"/>
                        @endif

                        @if ($errors->has('shipping'))
                            <x-alert.errors/>
                        @else
                            <x-alert>
                                {{ __('Please take a moment to double-check your product selection and shipping address before you proceed.') }}
                            </x-alert>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 md:items-end md:justify-between md:flex-row">
                        <div class="shrink-0">
                            <x-link label="Return to information" icon="arrow-left" :href="route('web.shop.checkout')"/>
                        </div>
                        
                        @if ($order->shipping_rate_id)
                            <div class="shrink-0">
                                <x-button.submit icon="arrow-right" label="Continue to payment"/>
                            </div>
                        @endif
                    </div>
                </form>
            @endif
        </div>
    </div>
</main>