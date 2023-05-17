<x-form.select
    :options="model('tax')
        ->readable()
        ->status('active')
        ->orderBy('name')
        ->get()
        ->transform(fn($tax) => [
            'value' => $tax->id,
            'label' => $tax->label,
        ])
        ->toArray()"
    {{ $attributes->except(['options']) }}
>
    <x-slot:foot 
        label="New Tax"
        :href="route('app.tax.create')"
        icon="plus"
    ></x-slot:foot>
</x-form.select>