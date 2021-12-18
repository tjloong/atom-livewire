<main class="min-h-screen">
    <section>
        <x-builder.hero slider>
            <x-builder.slider slide image="https://images.unsplash.com/photo-1639736867865-4828c8ad1163?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwzfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=500&q=60"/>
            <x-builder.slider slide image="https://images.unsplash.com/photo-1639738415512-1f122497ef9c?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=500&q=60"/>
            <x-builder.slider slide image="https://images.unsplash.com/photo-1639616714095-07d638ee2594?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxN3x8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60"/>
        </x-builder.hero>
    </section>

    <section>
        <x-builder.hero>
            <x-slot name="title">Atomic Bomb</x-slot>

            Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, eveniet eaque, quibusdam blanditiis ex sapiente consequatur error non perspiciatis mollitia sunt? Consectetur amet possimus, totam facilis beatae fugiat voluptatem nihil?
            
            <x-slot name="cta">
                <x-button>
                    Get Started
                </x-button>
            </x-slot>
        </x-builder.hero>
    </section>

    <section class="h-screen">
        <x-builder.faq :sets="$faq">
            <x-slot name="title">Frequently Asked Questions</x-slot>
            <x-slot name="subtitle">Can't find the answer you're looking for?</x-slot>
        </x-builder.faq>
    </section>
</main>
