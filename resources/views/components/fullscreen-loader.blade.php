<div x-data="fullscreenLoader">
    <div x-show="show" x-transition.opacity class="fixed inset-0 z-30 transition-all duration-200 ease-in-out">
        <div class="absolute opacity-50 w-full h-full bg-white"></div>
        <div class="absolute right-12 bottom-12 p-2 text-theme">
            <svg class="animate-spin"
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24"
                style="width: 45px; height: 45px"
            >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>
