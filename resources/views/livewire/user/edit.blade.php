<atom:modal name="atom.user.edit" wire:open="open">
@if ($user)
    <atom:_form>
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                @if ($user->exists && $user->trashed() && !$user->isAuth())
                    <atom:_button action="restore">@t('restore')</atom:_button>
                    <atom:_button action="delete" inverted>@t('delete')</atom:_button>
                @else
                    <atom:_button action="submit">@t('save')</atom:_button>

                    @if ($user->exists && !$user->isAuth())
                        <atom:_button action="trash" inverted>@t('trash')</atom:_button>
                    @endif
                @endif
            </div>

            <atom:separator/>

            <atom:_heading size="lg">
                @if ($user->exists) @t('edit-user')
                @else @t('create-user')
                @endif
            </atom:_heading>

            <atom:_input wire:model.defer="user.name" label="Name"/>
            <atom:_input type="email" wire:model.defer="user.email" label="Login Email"/>
            <atom:_input type="password" wire:model.defer="inputs.password" label="Password"/>
            <atom:_checkbox wire:model="inputs.is_blocked" label="Blocked"/>
        </div>
    </atom:_form>
@endif
</atom:modal>
