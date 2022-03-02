<div 
    x-data="pricingTable(@js($plan), @js($prices))"
    class="bg-white shadow rounded-lg border flex flex-col"
>
    <div class="flex-grow p-6">
        <div class="flex flex-col gap-6 h-full">
            <div>
                <div x-show="recurrings.length > 1" class="flex gap-2 items-center float-right">
                    <template x-for="(val, i) in recurrings" x-bind:key="`recurring-${i}`">
                        <div 
                            x-text="val"
                            x-bind:class="price.recurring === val ? 'bg-theme-dark text-white font-semibold' : 'cursor-pointer bg-gray-200'"
                            x-on:click="switchRecurring(val)"
                            class="text-xs py-1 px-2 rounded"
                        ></div>
                    </template>
                </div>
        
                <div class="font-semibold text-xl" x-text="plan.name"></div>
            </div>
    
            <div class="flex gap-2">
                <span class="font-medium" x-text="price.currency"></span>
                <span class="text-4xl font-extrabold" x-text="price.amount"></span>
                <span class="font-medium self-end" x-text="price.recurring"></span>
            </div>
    
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
        </div>
    </div>

    <div class="flex-shrink-0 bg-gray-100 p-6">
        <x-button x-on:click.prevent="register" size="md" class="w-full">
            <span x-text="cta"></span>
        </x-button>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('pricingTable', (plan, prices) => ({
                plan,
                prices,
                price: prices[0],
                
                get recurrings () {
                    return this.prices.map(val => (val.recurring))
                },

                get cta () {
                    return this.plan.data?.cta || 'Sign Up'
                },

                switchRecurring (val) {
                    if (this.price.recurring === val) return

                    this.price = this.prices.find(price => (price.recurring === val))
                },

                register () {
                    const route = '{{ route('register', ['ref' => $attributes->get('register-ref') ?? 'pricing-table']) }}'
                    const params = { plan: this.plan.slug, recurring: this.price.recurring }
                    const query = new URLSearchParams(params).toString()
                    
                    window.location = `${route}&${query}`
                },
            }))
        })
    </script>
</div>