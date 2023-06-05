<x-form.select
    :options="model('product')->readable()->orderBy('code')->orderBy('name')->get()->map(fn($product) => [
        'value' => $product->id,
        'label' => $product->name,
        'small' => '#'.$product->code,
        'remark' => currency($product->price),
    ])"
    {{ $attributes->except('options') }}
/>
