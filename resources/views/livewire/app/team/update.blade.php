<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$team->name" back>
        <x-button.delete inverted can="team.manage"
            title="Delete Team"
            message="This will DELETE the team. Are you sure?"
        />
    </x-page-header>

    @livewire(lw('app.team.form'), compact('team'))
</div>