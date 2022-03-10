<x-box>
    <x-slot name="header">Current Subscription Plans</x-slot>

    <div class="grid divide-y">
        @foreach ($subscriptions as $subscription)
            @json($subscription)
        @endforeach
    </div>
</x-box>