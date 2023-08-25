<div class="box bg-white border shadow flex flex-col divide-y rounded-lg" {{
    $attributes->except(['heading', 'class'])
}}>
    @isset($heading)
        {{ $heading }}
    @elseif ($heading = $attributes->get('heading'))
        <x-heading class="py-2 px-4" sm
            :title="$attributes->get('heading')"/>
    @endif

    <div {{ $attributes->merge([
        'class' => 'p-1',
    ])->only('class') }}>
        {{ $slot }}
    </div>

    @isset ($foot)
        <div class="py-3 px-4 bg-slate-100 rounded-b-lg">
            {{ $foot }}
        </div>
    @endisset
</div>
