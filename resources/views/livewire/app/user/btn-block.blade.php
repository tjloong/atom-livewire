<div>
    @if (!$user->trashed())
        @if ($user->blocked())
            <x-button.confirm color="gray" inverted icon="play"
                label="Unblock User"
                title="Unblock User"
                message="This will UNBLOCK the user. Are you sure?"
                callback="unblock"
            />
        @else
            <x-button.confirm color="red" inverted icon="block"
                label="Block User"
                title="Block User"
                message="This will BLOCK the user. Are you sure?"
                callback="block"
            />
        @endif
    @endif
</div>