<div class="max-w-screen-md">
    <x-heading title="app.label.my-profile" lg/>

    <div class="flex flex-col gap-8">
        @livewire('app.settings.profile.login', key('login'))
        @livewire('app.settings.profile.password', key('password'))
    </div>
</div>