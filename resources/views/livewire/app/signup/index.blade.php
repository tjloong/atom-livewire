<div 
    x-data="{ signupId: @js($signupId) }"
    x-init="signupId && $wire.emit('updateSignup', signupId)"
    class="max-w-screen-xl mx-auto">
    <x-heading title="Sign-Ups" 2xl/>
    
    @livewire('app.signup.listing', key('listing'))
    @livewire('app.signup.update', key('update'))
</div>