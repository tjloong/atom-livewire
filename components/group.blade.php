@php
$type = $attributes->get('type');
$gap = $attributes->get('gap');
$max = $attributes->get('max');
$attrs = $attributes->except('type');
@endphp

@if ($type === 'checkbox')
    <div {{ $attrs->class(['group/group flex flex-col gap-2 [&>[data-atom-heading]]:mb-1']) }}>
        {{ $slot }}
    </div>
@elseif ($type === 'buttons')
    <div {{ $attrs->class($gap
        ? ['group/group flex items-center flex-wrap gap-3']
        : [
            'group/group flex items-center *:rounded-none',
            '*:-ml-px first:*:ml-0',
            'first:*:rounded-l-md last:*:rounded-r-md',
        ]
    ) }}>
        {{ $slot }}
    </div>
@elseif ($type === 'avatars')
    <div {{ $attrs->class(['group/group flex items-center *:-ml-2 first:*:-ml-0']) }}>
        {{ $slot }}
    </div>
@elseif ($type === 'badges')
    <div
        @if ($max)
        x-data="{
            get badges () {
                return Array.from(this.$root.querySelectorAll(':scope > *'))
                    .filter(child => (!child.hasAttribute('data-overlimit-badge')))
            },

            isOverLimit () {
                return this.badges.length > @js($max)
            },
        }"
        @endif
        {{ $attrs->class([
            'group/group flex items-center gap-2 flex-wrap',
            $max ? '[&>*:nth-child(n+'.($max + 1).')]:hidden' : '',
        ]) }}>
        {{ $slot }}

        @if ($max)
            <div style="display: inherit" data-overlimit-badge>
                <div
                    x-show="isOverLimit()"
                    x-text="`+${badges.length - @js($max)}`"
                    class="text-sm text-muted font-medium">
                </div>
            </div>
        @endif
    </div>
@else
    <div {{ $attrs->class(['group/group flex flex-col gap-6 [&>[data-atom-heading]]:-mb-3']) }}>
        {{ $slot }}
    </div>
@endif
