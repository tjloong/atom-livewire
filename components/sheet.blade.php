@php
$name = $attributes->get('name') ?? (method_exists($this, 'componentName') ? $this->componentName() : null);
$label = t($attributes->get('label'));
$inset = $attributes->get('inset');
$header = $header ?? $attributes->get('header');
$breadcrumb = $header !== false && !($header instanceof \Illuminate\View\ComponentSlot);
$transparent = $attributes->get('transparent');
$footer = session('__sheet_footer');
@endphp

<div
wire:ignore.self
x-cloak
x-data="sheet({ name: @js($name), label: @js($label) })"
x-transition.opacity.duration.200
x-on:sheet-show.window="show($event.detail)"
x-on:sheet-label.window="setLabel($event.detail)"
x-on:sheet-back.window="back($event.detail)"
x-on:sheet-refresh.window="refresh($event.detail)"
x-on:scroll="scroll()"
data-atom-sheet="{{ $name }}"
class="{{ collect([
    'group/sheet fixed inset-0 overflow-auto',
    'hidden opacity-0 transition-opacity duration-200 ease-in-out bg-white',
    'lg:ml-64 lg:group-has-[[data-atom-panel-sidebar-show]]/panel:ml-0',
    'group-has-[[data-atom-panel-navbar]]/panel:pt-[4.5rem]',
])->join(' ') }}"
{{ $attributes->except(['name', 'label', 'inset', 'class']) }}>
    <div class="flex flex-col w-full min-h-full">
        @if ($header !== false)
            <div class="shrin-0 h-20 px-6 w-full">
                @if ($breadcrumb)
                    <div class="flex items-center w-full h-full">
                        <atom:breadcrumb :hide-single-trace="false">
                            @isset ($actions) {{ $actions }} @endisset
                        </atom:breadcrumb>
                    </div>
                @else
                    {{ $header }}
                @endif
            </div>
        @endif

        <div class="grow w-full mx-auto {{ $attributes->get('class', 'max-w-screen-xl') }} {{ $inset ? '' : 'px-6 pb-6 first:pt-6' }}">
            @if ($slot->isEmpty()) <atom:skeleton/>
            @else {{ $slot }}
            @endif
        </div>

        @if ($footer)
            {{ $footer }}
        @endif
    </div>
</div>