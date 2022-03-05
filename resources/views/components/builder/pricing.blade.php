<div 
    x-data="pricingTable(@js($plan), @js($prices), @js($recurrings), @js($cta))"
    class="bg-white shadow rounded-lg border flex flex-col"
>
    <div class="flex-grow p-6">
        <div class="flex flex-col gap-6 h-full">
            @isset($header)
                {{ $header }}
            @else
                <div>
                    <div x-show="recurrings.length > 1" class="flex gap-2 items-center float-right">
                        <template x-for="(val, i) in recurrings" x-bind:key="`recurring-${i}`">
                            <div 
                                x-text="val.label"
                                x-bind:class="price.expired_after === val.value ? 'bg-theme-dark text-white font-semibold' : 'cursor-pointer bg-gray-200'"
                                x-on:click="switchRecurring(val)"
                                class="text-xs py-1 px-2 rounded"
                            ></div>
                        </template>
                    </div>
            
                    <div class="font-semibold text-xl" x-text="plan.name"></div>
                </div>
            @endif
    
            @isset($price)
                {{ $price }}
            @else
                <div class="flex gap-2">
                    <span class="font-medium" x-text="price.currency"></span>
                    <span class="text-4xl font-extrabold" x-text="currency(price.amount)"></span>
                    <span class="font-medium self-end" x-text="`/ ${price.recurring}`"></span>
                </div>
            @endif
    
            @isset($body)
                {{ $body }}
            @else
                <div class="text-gray-500 font-medium" x-text="plan.excerpt"></div>
        
                <div class="flex-grow">
                    <div class="grid gap-2">
                        <template x-for="(feature, i) in plan.features" x-bind:key="`feature-${i}`">
                            <div class="flex gap-2 items-center">
                                <x-icon name="check" color="green"/>
                                <div x-text="feature"></div>
                            </div>
                        </template>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="flex-shrink-0 bg-gray-100 p-6">
        <x-button x-bind:href="href" size="md" class="w-full">
            {{ $cta['text'] }}
        </x-button>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pricingTable', (plan, prices, recurrings, cta) => ({
                cta,
                plan,
                prices,
                recurrings,
                price: prices.find(val => val.is_default) || prices[0],
                
                get href () {
                    const href = this.cta?.href || '/'
                    const params = { plan: this.plan.slug, price: this.price.id }
                    const query = new URLSearchParams(params).toString()

                    return `${href}&${query}`
                },

                switchRecurring (recurring) {
                    if (this.price.expired_after === recurring.value) return

                    this.price = this.prices.find(price => (price.expired_after === recurring.value))
                },
            }))
        })
    </script>
</div>