<main class="min-h-screen max-w-screen-xl mx-auto px-6 py-20 grid gap-10">
    <section class="grid gap-4">
        <div class="text-xl font-bold">Hero</div>

        <div class="border rounded-md overflow-hidden shadow">
            <x-builder.hero
                :image="[
                    'url' => 'https://images.unsplash.com/photo-1642940792376-7819eeaa84a5?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxN3x8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=60',
                    'position' => 'right',
                ]"
            >
                <div class="grow">
                    <div class="grid gap-6 p-6">
                        <h1 class="text-3xl font-bold">
                            Lorem ipsum dolor sit amet consectetur.
                        </h1>

                        <h2 class="text-lg font-medium">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, eveniet eaque, quibusdam blanditiis ex sapiente consequatur error non perspiciatis mollitia sunt? Consectetur amet possimus, totam facilis beatae fugiat voluptatem nihil?
                        </h2>

                        <div>
                            <x-button>
                                Get Started
                            </x-button>
                        </div>
                    </div>
                </div>
            </x-builder.hero>
        </div>
    </section>

    <section class="grid gap-4">
        <div class="text-xl font-bold">Hero Slider</div>

        <div class="border rounded-md overflow-hidden shadow">
            <x-builder.hero slider>
                <x-builder.slider slide image="https://images.unsplash.com/photo-1642887896814-0818d2d2ee2a?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxMnx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=60"/>
                <x-builder.slider slide image="https://images.unsplash.com/photo-1642783632165-e13d344adc1d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwzNHx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=60"/>
                <x-builder.slider slide image="https://images.unsplash.com/photo-1639439815255-824da6a3adfe?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw3Mnx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=60"/>
                <x-builder.slider slide image="https://images.unsplash.com/photo-1642922808971-0529d555c105?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw5MXx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=60"/>
            </x-builder.hero>
        </div>
    </section>

    <section class="grid gap-4">
        <div class="text-xl font-bold">FAQ</div>

        <div class="border rounded-md shadow">
            <x-builder.faq :sets="[
                [
                    'question' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                    'answer' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus ab laboriosam, laborum deserunt aperiam, soluta provident voluptas repudiandae ut ducimus esse quis! Quod, incidunt! Accusantium ducimus veritatis reiciendis deserunt sequi?',
                ],
                [
                    'question' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                    'answer' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus ab laboriosam, laborum deserunt aperiam, soluta provident voluptas repudiandae ut ducimus esse quis! Quod, incidunt! Accusantium ducimus veritatis reiciendis deserunt sequi?',
                ],
            ]">
                <x-slot name="title">Frequently Asked Questions</x-slot>
                <x-slot name="subtitle">Can't find the answer you're looking for?</x-slot>
            </x-builder.faq>
        </div>
    </section>

    @if ($this->plans)
        <section class="grid gap-4">
            <div class="text-xl font-bold">Pricing Table</div>

            <div class="max-w-screen-md mx-auto">
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($this->plans as $plan)
                        <x-builder.pricing 
                            :plan="$plan->toArray()" 
                            :prices="$plan->planPrices
                                ->map(fn($planPrice) => $planPrice->append('recurring'))
                                ->toArray()"
                            :trial="$plan->trial"
                        >
                            <x-slot:cta>
                                @foreach ($plan->planPrices as $planPrice)
                                    <x-button 
                                        x-show="variant === '{{ $planPrice->recurring }}'"
                                        block
                                        :href="auth()->user() && Route::has('billing')
                                            ? route('billing')
                                            : (Route::has('register') 
                                                ? route('register', ['ref' => 'pricing', 'plan' => $plan->slug, 'price' => $planPrice->id])
                                                : '#'
                                            )
                                        " 
                                        size="md">
                                        {{ $plan->cta }}
                                    </x-button>
                                @endforeach
                            </x-slot:cta>
                        </x-builder.pricing>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</main>
