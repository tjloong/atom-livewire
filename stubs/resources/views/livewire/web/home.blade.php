<main class="min-h-screen">
    <section class="bg-indigo-800">
        <x-builder.hero :dark="true">
            <x-slot name="title">Atomic Bomb</x-slot>
            <x-slot name="content">Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, eveniet eaque, quibusdam blanditiis ex sapiente consequatur error non perspiciatis mollitia sunt? Consectetur amet possimus, totam facilis beatae fugiat voluptatem nihil?</x-slot>
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
    
    <footer>
        <x-builder.footer
            facebook="https://facebook.com/jiannius"
            instagram="https://insta.net"
            phone="+60123223344"
            email="hello@jiannius.com"
            copyright="Jiannius Technologies Sdn Bhd. All right reserved."
        />
    </footer>
</main>
