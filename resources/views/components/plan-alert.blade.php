<x-box>
    <div class="flex flex-col items-center justify-center gap-4 p-6">
        <x-icon name="circle-exclamation" size="48" class="text-red-300"/>

        <div class="text-center">
            <div class="text-lg font-semibold">
                {{ __($attributes->get('title', 'You need to upgrade your plan.')) }}
            </div>
            <div class="font-medium text-gray-500">
                {{ __($attributes->get('message', 'Please upgrade your plan.')) }}
            </div>
        </div>

        <x-button label="Go to Billing" :href="route('app.settings', ['billing'])" icon="gear"/>
    </div>
</x-box>
