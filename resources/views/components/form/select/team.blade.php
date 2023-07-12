@if (has_table('teams'))
    @props([
        'id' => $attributes->get('id', 'team'),
        'label' => $attributes->get('label', 'Team'),
        'options' => $attributes->get('options'),
    ])

    <x-form.select :id="$id"
        :label="$label"
        :options="collect(
            $options ?? model('team')->readable()->orderBy('name')->get()
        )->map(fn($team) => [
            'value' => $team->id,
            'label' => $team->name,
        ])->toArray()"
        {{ $attributes->except('id', 'label', 'options') }}
    />
@endif