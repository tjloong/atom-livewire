<atom:card>
    <atom:_form>
        <atom:_heading size="lg">@t('login-information')</atom:_heading>
        <atom:_input wire:model.defer="user.name" label="login-name"/>
        <atom:_input type="email" wire:model.defer="user.email" label="login-email"/>
        <atom:_button action="submit">@t('save')</atom:_button>
    </atom:_form>
</atom:card>
