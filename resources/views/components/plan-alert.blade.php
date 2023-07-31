<x-box>
    <div class="flex flex-col items-center justify-center gap-4 p-6">
        <x-icon name="circle-exclamation 2x" class="text-red-400"/>

        <div class="text-center">
            <div class="text-lg font-semibold">
                {{ __($attributes->get('title', 'You need to upgrade your plan.')) }}
            </div>
            <div class="font-medium text-gray-500">
                {{ __($attributes->get('message', 'Please upgrade your plan.')) }}
            </div>
        </div>

        <x-button color="theme" icon="gear"
            label="Go to Billing" 
            :href="route('app.settings', 'billing')"
        />
    </div>
</x-box>
