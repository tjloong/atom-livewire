<div class="max-w-screen-lg mx-auto flex flex-col gap-10">
    <x-logo class="w-32 h-20"/>

    <div class="flex flex-col gap-1">
        <div class="text-3xl font-bold">
            {{ tr('app.onboarding.your-account-setup-is-completed') }}
        </div>
    
        <div class="text-gray-500 text-lg font-medium">
            {{ tr('app.onboarding.we-are-excited-to-have-you') }}
        </div>
    </div>

    <div>
        <x-button icon="house" label="app.label.back-to-home" md :href="$redirect ?? user()->home()"/>
    </div>
</div>
