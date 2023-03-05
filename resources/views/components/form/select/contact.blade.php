<x-form.select
    :label="$attributes->get('label', 'Contact')"
    {{ $attributes->except(['label', 'options']) }}
    :options="model('contact')
        ->readable()
        ->when($attributes->get('category'), fn($q, $category) => $q->where('category', $category))
        ->when($attributes->get('type'), fn($q, $type) => $q->where('type', $type))
        ->orderBy('name')
        ->get()
        ->transform(fn($contact) => [
            'value' => $contact->id,
            'label' => $contact->name,
            'small' => collect([$contact->email, $contact->phone])->filter()->join(' | '),
            'avatar' => $contact->avatar,
        ])
        ->toArray()"
/>
