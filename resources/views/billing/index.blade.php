<div 
    x-cloak 
    x-data="{ showPlans: @js(!$subscriptions->count()) }" 
    x-on:cancel-plan-change="showPlans = false" 
    class="grid gap-6"
>
    <div x-show="showPlans">
        @livewire('atom.billing.plans', compact('plans', 'subscriptions'), key('plans'))
    </div>
    
    <div x-show="!showPlans">
        @livewire('atom.billing.subscriptions', compact('subscriptions'), key('subscriptions'))
    </div>
</div>