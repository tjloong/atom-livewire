<div class="max-w-screen-sm">
    <x-heading title="Payment Integration"/>

    <div class="flex flex-col gap-4">
        @foreach (config('atom.payment_gateway') as $val)
            @livewire('app.settings.integration.payment.'.$val, key($val))
        @endforeach
    </div>
</div>
