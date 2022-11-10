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
    class="relative flex flex-col divide-y bg-white border shadow rounded-lg"
>
    @isset($header) {{ $header }} @endif

    @if ($slot->isEmpty())
        @isset($empty) {{ $empty }}
        @else <x-empty-state/>
        @endif
    @else
        <div {{ $attributes->class([
            'w-full overflow-auto rounded-b-lg',
            $attributes->get('class', 'max-h-screen'),
        ])->only('class') }}>
            <table class="w-max min-w-full divide-y divide-gray-200">
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
    @endif
</div>
