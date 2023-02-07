<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$this->role->name" back>
        <x-button.delete inverted can="role.manage"
            title="Delete Role"
            message="This will DELETE the role. Are you sure?"
        />
    </x-page-header>

    @livewire(lw('app.role.form'), compact('role'))
</div>