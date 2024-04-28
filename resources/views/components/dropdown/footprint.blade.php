@php
    $model = $attributes->get('model');
    $placement = $attributes->get('placement', 'bottom-end');

    $auditable = [
        'type' => $attributes->get('auditable-type'),
        'id' => $attributes->get('auditable-id'),
    ];

    $footprints = collect($model->footprint)
        ->map(fn($value, $key) => [
            'timestamp' => $model->footprint($key.'.timestamp'),
            'description' => $model->footprint($key.'.description'),
        ])
        ->sortBy('timestamp')
        ->map(fn($value) => data_get($value, 'description'))
        ->values();
@endphp

<div 
    x-cloak
    x-data="{ open: false }"
    x-on:click.away="open = false">
    <div x-ref="anchor" x-on:click.stop="show = true">
        @if ($slot->isNotEmpty()) {{ $slot }}
        @else <x-button sm icon="shoe-prints"/>
        @endif
    </div>

    <div 
        x-ref="dropdown"
        x-show="open"
        x-anchor.{{ $placement }}="$refs.anchor"
        x-transition.opacity.duration.300
        class="absolute right-0 z-20 border border-gray-300 bg-white rounded-md shadow-lg w-max">
        <div class="flex flex-col divide-y">
            <div class="text-sm font-medium text-gray-500 flex items-center gap-2 py-2 px-4">
                <x-icon name="shoe-prints" class="text-xs"/> {{ tr('app.label.footprint') }}
            </div>

            @forelse ($footprints as $item)
                <div class="text-sm py-2 px-4">
                    {!! $item !!}
                </div>
            @empty
                <div class="px-8">
                    <x-no-result title="app.label.no-footprint" xs/>
                </div>
            @endforelse

            @if (data_get($auditable, 'type') && data_get($auditable, 'id') && has_route('app.audit'))
                <div class="flex items-center justify-center py-2 px-4">
                    <x-link label="app.label.audit-trail:2" class="text-sm" :href="route('app.audit', ['filters' => [
                        'auditable_id' => data_get($auditable, 'id'),
                        'auditable_type' => data_get($auditable, 'type'),
                    ]])"/>
                </div>
            @endif
        </div>
    </div>
</div>