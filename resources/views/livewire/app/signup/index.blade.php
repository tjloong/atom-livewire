<div x-init="@js($signupId) && $wire.emit('updateSignup', @js($signupId))" class="max-w-screen-xl mx-auto">
    <x-heading title="signup.heading.signup" xl/>
    @livewire('app.signup.listing', key('listing'))
</div>