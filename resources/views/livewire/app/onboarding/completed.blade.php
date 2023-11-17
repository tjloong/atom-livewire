<div class="max-w-screen-lg mx-auto flex flex-col gap-10">
    <x-logo class="w-40"/>

    <div class="flex flex-col gap-1">
        <div class="text-3xl font-bold">
            {{ tr('onboarding.completed.title') }}
        </div>
    
        <div class="text-gray-500 text-lg font-medium">
            {{ tr('onboarding.completed.subtitle') }}
        </div>
    </div>

    <div>
        <x-button icon="house" label="common.label.back-to-home" md :href="user()->home()"/>
    </div>
</div>
