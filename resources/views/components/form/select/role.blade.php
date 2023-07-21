@if (has_table('roles'))
    @props([
        'id' => $attributes->get('id', 'role'),
        'label' => $attributes->get('label', 'Role'),
        'options' => $attributes->get('options'),
    ])

    <x-form.select :id="$id"
        :label="$label"
        :options="collect(
            $options ?? model('role')->readable()
                ->orderBy('seq')
                ->orderBy('name')
                ->get()
        )->map(fn($role) => [
            'value' => $role->id,
            'label' => $role->name,
        ])->toArray()"
        {{ $attributes->except('id', 'label', 'options') }}
    />
@endif