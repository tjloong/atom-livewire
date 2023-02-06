<div class="flex items-center gap-2">
    @if ($user->trashed())
        <x-button.delete inverted
            title="Delete User"
            message="This will permanently DELETE this user from the system. This action CANNOT BE UNDONE. Are you sure?"
        />

        <x-button.confirm color="gray" inverted icon="refresh"
            label="Restore"
            title="Restore User"
            message="This will restore the user. Are you sure?"
            callback="restore"
        />
    @else
        <x-button.delete inverted label="Move to Trash"
            title="Move User to Trash"
            message="This will move the user to Trash. You can restore the user later if you regret this action."
        />
    @endif
</div>