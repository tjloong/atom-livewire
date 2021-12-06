<div x-data="fullscreenLoader">
    <div x-show="show" x-transition.opacity class="fixed inset-0 z-30 transition-all duration-200 ease-in-out">
        <div class="absolute opacity-50 w-full h-full bg-white"></div>
        <div class="absolute right-12 bottom-12 p-2 text-theme">
            <x-icon name="radio-circle" animation="burst" size="45px"/>
        </div>
    </div>
</div>
