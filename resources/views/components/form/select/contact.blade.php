@props([
    'id' => $attributes->get('id', 'contact'),
    'label' => $attributes->get('label', 'Contact'),
    'options' => $attributes->get('options'),
    'type' => $attributes->get('type'),
    'category' => $attributes->get('category'),
])

<x-form.select :id="$id"
    :label="$label"
    :options="collect(
        $options
        ?? model('contact')->readable()
            ->filter([
                'search' => $this->getSelectInputSearch($id),
                'type' => $type,
                'category' => $category,
            ])
            ->orderBy('name')
            ->take(100)
            ->get()
    )->transform(fn($contact) => [
        'value' => $contact->id,
        'label' => $contact->name,
        'small' => collect([$contact->email, $contact->phone])->filter()->join(' | '),
        'avatar' => $contact->avatar,
    ])->toArray()"
    {{ $attributes->except(['id', 'label', 'options']) }}
>
    @isset($foot)
        <x-slot:foot 
            :label="$foot->attributes->get('label')"
            :icon="$foot->attributes->get('icon')"
            :href="$foot->attributes->get('href')"
        >
            {{ $foot }}
        </x-slot:foot>
    @else
        <x-slot:foot label="New Contact" icon="add" 
            :href="route(atom_lw('app.contact.create'), [$category])"
        ></x-slot:foot>
    @endisset
</x-form.select>
