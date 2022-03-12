<div class="grid gap-6">
    @livewire('atom.account.authentication.profile', ['user' => $user], key('profile'))
    @livewire('atom.account.authentication.password', ['user' => $user], key('password'))
</div>