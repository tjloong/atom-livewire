@props([
    'uid' => $attributes->get('uid', 'table'),
])

<div 
    id="{{ $uid }}"
    x-cloak
    x-data="{
        uid: @js($uid),
        get isEmpty () {
            const rows = Array.from($el.querySelectorAll('table > tbody > tr')).length
            return rows <= 0
        },
    }"
    class="relative flex flex-col divide-y bg-white border shadow rounded-lg overflow-hidden"
>
    @isset($header) {{ $header }} @endif

    <div x-show="isEmpty">
        @isset($empty) {{ $empty }}
        @else <x-empty-state/>
        @endif
    </div>

    <div x-show="!isEmpty" class="w-full overflow-auto max-h-screen">
        <table class="w-max divide-y divide-gray-200 md:w-full md:max-w-full">
            @isset($thead)
                <thead>
                    <tr>
                        {{ $thead }}
                    </tr>
                </thead>
            @endisset

            <tbody class="bg-white">
                {{ $slot }}
            </tbody>

            @isset($tfoot)
                <tfoot>
                    {{ $tfoot }}
                </tfoot>
            @endisset
        </table>
    </div>
</div>
