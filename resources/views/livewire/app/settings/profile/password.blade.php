<atom:card>
    <atom:_form>
        <atom:_heading size="lg">@t('change-password')</atom:_heading>
        <atom:_input type="password" wire:model.defer="password.current" label="current-password"/>
        <atom:_input type="password" wire:model.defer="password.new" label="new-password"/>
        <atom:_input type="password" wire:model.defer="password.new_confirmation" label="confirm-new-password"/>
        <atom:_button action="submit">@t('save')</atom:_button>
    </atom:_form>
</atom:card>
