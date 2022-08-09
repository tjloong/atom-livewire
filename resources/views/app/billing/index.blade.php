<div class="max-w-screen-md mx-auto">
    <x-page-header title="Billing Management"/>

    <div class="grid gap-6">
        @livewire('atom.app.billing.current-subscriptions', compact('account'), key('current-subscriptions'))
        @livewire('atom.app.account-payment.listing', compact('account'), key('payment-history'))
        @livewire('atom.app.billing.cancel-auto-billing-modal', key('cancel-auto-billing-modal'))
    </div>
</div>