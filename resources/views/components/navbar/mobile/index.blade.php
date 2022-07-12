<nav {{ $attributes->merge(['class' => 'fixed bottom-0 left-0 right-0 z-40 p-4']) }}>
    <div class="max-w-screen-xl mx-auto">
        <div class="flex items-center justify-evenly gap-4">
            {{ $slot }}
        </div>
    </div>
</nav>
