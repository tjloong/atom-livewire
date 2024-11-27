@aware(['variant'])

@php
$classes = $attributes->classes()
    ->add('py-3 px-4 flex')
    ->add(match ($variant) {
        'card' => 'border border-zinc-200 shadow-sm rounded-lg hover:bg-zinc-50 has-[:checked]:bg-zinc-100',
        default => '',
    });

$attrs = $attributes->class($classes);
@endphp

<label {{ $attrs->only('class') }}>
    <div class="shrink-0 py-[0.1rem] {{ match ($variant) {
        'card' => 'order-last pl-2',
        default => 'pr-3',
    } }}">
        <input type="radio"
            x-model="radioValue"
            x-bind:name="groupName"
            class="peer hidden"
            {{ $attrs->except('class') }}>
        
        <div role="radio" class="size-5 rounded-full border border-zinc-300 shadow-sm bg-white peer-checked:border-primary peer-checked:border-4"></div>
    </div>

    <div class="grow">
        {{ $slot }}
    </div>
</label>
