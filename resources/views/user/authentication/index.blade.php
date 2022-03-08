<div class="grid gap-6">
    @livewire('atom.user.authentication.profile', ['user' => $user], key('profile'))
    @livewire('atom.user.authentication.password', ['user' => $user], key('password'))
</div>