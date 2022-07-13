<div class="grid gap-6">
    @livewire('atom.account.authentication.profile', ['user' => $user], key('profile'))
    @livewire('atom.account.authentication.password', ['user' => $user], key('password'))

    @if (session('webview'))
        <div>
            <x-button color="red" icon="log-out"
                label="Logout"
                x-on:click="$dispatch('confirm', {
                    title: '{{ __('Logout') }}',
                    message: '{{ __('Are you sure to logout?') }}',
                    type: 'error',
                    onConfirmed: () => window.location = '{{ route('login', ['logout' => true]) }}',
                })"
            />
        </div>
    @endif
</div>