<div class="space-y-6">
    <atom:card>
        <atom:_form>
            <atom:_heading size="xl">@t('reset-password')</atom:_heading>

            <atom:dd label="email" block>@e(get($inputs, 'email'))</atom:dd>

            <atom:_input type="password" wire:model.defer="inputs.password" label="password"/>
            <atom:_input type="password" wire:model.defer="inputs.password_confirmation" label="confirm-password"/>
            
            @if (!$errors->first('inputs.password') && !$errors->first('inputs.password_confirmation') && $errors->first())
                <atom:inform variant="danger">
                    @e($errors->first())
                </atom:inform>
            @endif

            <atom:_button action="submit" block>@t('reset-password')</atom:_button>
        </atom:_form>
    </atom:card>


    <atom:link icon="back" :href="route('login')">@t('back-to-login')</atom:link>
</div>
