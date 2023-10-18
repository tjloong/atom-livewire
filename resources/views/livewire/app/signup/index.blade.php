<div 
    x-data="{ signupId: @js($signupId) }"
    x-init="signupId && $wire.emit('updateSignup', signupId)"
    class="max-w-screen-xl mx-auto">
    <x-heading title="atom::signup.heading.signup" 2xl/>
    @livewire('app.signup.listing', key('listing'))
</div>