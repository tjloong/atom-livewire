<div
    x-data="{
        show: false,
        open () { document.documentElement.classList.add('overflow-hidden'); this.show = true },
        close () { document.documentElement.classList.remove('overflow-hidden'); this.show = false },
    }"
    x-on:{{ $uid }}-open.window="open()"
    x-on:{{ $uid }}-close.window="close()"
    {{ $attributes }}
>
    <div x-data x-show="show" x-transition.opacity class="fixed inset-0 z-30">
        <div class="absolute inset-0 bg-black opacity-50" x-on:click="$dispatch('{{ $uid }}-close')"></div>

        <div class="absolute top-0 bottom-0 right-0 w-10/12 bg-white shadow-md pt-3 pb-10 px-6 overflow-auto md:max-w-sm">
            <div class="flex items-center justify-between space-x-2 mb-6">
                @isset($title)
                    <div class="text-lg font-semibold">{{ $title }}</div>
                @endisset
        
                <a class="text-gray-800 flex items-center justify-center" x-on:click.prevent="$dispatch('{{ $uid }}-close')">
                    <x-icon name="xmark"/>
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>
</div>
