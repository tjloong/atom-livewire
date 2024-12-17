<div class="lg:max-w-xl space-y-6">
    <atom:_heading size="xl">@t('my-profile')</atom:_heading>

    <atom:card>
        <atom:_form>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="user.name" label="name"/>
                <atom:_input type="email" wire:model.defer="user.email" label="login-email"/>
            </div>

            <atom:separator>@t('change-password')</atom:separator>

            <atom:_input type="password" wire:model.defer="password.current" label="current-password"/>

            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input type="password" wire:model.defer="password.new" label="new-password"/>
                <atom:_input type="password" wire:model.defer="password.new_confirmation" label="confirm-new-password"/>        
            </div>

            <atom:_button action="submit">@t('save')</atom:_button>
        </atom:_form>
    </atom:card>
</div>
