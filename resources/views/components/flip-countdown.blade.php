@props([
    'to' => $attributes->get('to'),
    'id' => component_id($attributes, 'countdown'),
])

<div
    x-data="{
        startTick (tick) {
            const counter = Tick.count.down(@js($to))

            counter.onupdate = (value) => {
                tick.value = value
            }
        }
    }"
    x-init="Tick.DOM.create($el, { didInit: startTick })"
    class="tick"
    id="{{ $id }}"
>
    <div 
        aria-hidden="true" 
        data-repeat="true" 
        data-transform="preset(d, h, m, s) -> delay"
        class="flex items-center justify-center gap-4 flex-wrap"
    >
        <div class="flex flex-col gap-3 items-center justify-center">
            <div 
                data-key="value" 
                data-repeat="true" 
                data-transform="pad(00) -> split -> delay"
                @if (isset($flip)) 
                    {{ $flip->attributes->class([
                        'font-medium shrink-0 flex items-center',
                        $flip->attributes->get('class', 'text-2xl md:text-5xl'),
                    ]) }}
                @else
                    class="text-2xl md:text-5xl font-medium shrink-0 flex items-center"
                @endif
            >
                <span data-view="flip"></span>
            </div>

            <span 
                data-key="label" 
                data-view="text" 

                @if (isset($label)) 
                    {{ $label->attributes->class([
                        'font-medium',
                        $label->attributes->get('class', 'md:text-lg'),
                    ]) }}
                @else
                    class="md:text-lg font-medium"
                @endif
            ></span>
        </div>
    </div>
</div>
