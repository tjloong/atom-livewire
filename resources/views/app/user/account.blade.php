<div class="max-w-md mx-auto">
    <x-page-header title="My Account"/>
    @livewire('atom.user.form', ['user' => auth()->user()])
</div>
