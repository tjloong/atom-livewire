<atom:modal name="atom.user.edit" wire:open="open">
@if ($user)
    <atom:_form>
        <atom:_heading size="xl">
            @if ($user->exists) @t('edit-user')
            @else @t('create-user')
            @endif
        </atom:_heading>

        <atom:_input wire:model.defer="user.name" label="Name"/>
        <atom:_input type="email" wire:model.defer="user.email" label="Login Email"/>
        <atom:_input type="password" wire:model.defer="inputs.password" label="Password"/>
        <atom:_checkbox wire:model="inputs.is_blocked" label="Blocked"/>

        <atom:group type="buttons">
            @if ($user->exists && $user->trashed() && !$user->isAuth())
                <atom:_button action="restore">@t('restore')</atom:_button>
                <atom:_button action="delete" inverted>@t('delete')</atom:_button>
            @else
                <atom:_button action="submit">@t('save')</atom:_button>

                @if ($user->exists && !$user->isAuth())
                    <atom:_button action="trash" inverted>@t('trash')</atom:_button>
                @endif
            @endif
        </atom:group>
    </atom:_form>
@endif
</atom:modal>
