<div class="max-w-screen-lg mx-auto flex flex-col gap-10">
    <x-logo class="w-32 h-20"/>

    <div class="flex flex-col gap-1">
        <div class="text-3xl font-bold">
            {{ tr('app.label.your-account-setup-is-completed') }}
        </div>
    
        <div class="text-gray-500 text-lg font-medium">
            {{ tr('app.label.we-are-excited-to-have-you-as-our-new-friend') }}
        </div>
    </div>

    <div>
        <x-button icon="house" label="app.label.back-to-home" :href="$redirect ?? user()->home()"/>
    </div>
</div>
