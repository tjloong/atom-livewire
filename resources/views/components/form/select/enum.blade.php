@props([
    'getOptions' => function() use ($attributes) {
        return enum($attributes->get('enum'))
            ->when($attributes->get('exclude'), fn($enums, $exclude) => 
                $enums->reject(fn($enum) => in_array($enum->name, (array) $exclude))->values()
            )
            ->map(fn($val) => $val->option())
            ->toArray();
    }
])

<x-form.select :options="$getOptions()" {{ $attributes->except('options') }}/>