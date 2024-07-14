<div class="flex flex-col gap-4">
    @recaptcha

    <x-form recaptcha="login">
        <x-fieldset inputs>
            <div class="text-2xl font-bold">
                {!! tr('app.label.signin') !!}
            </div>

            @if ($errors->any())
                <x-inform :message="$errors->first()" type="error"/>
            @elseif ($message = session('message'))
                <x-inform :message="$message"/>
            @endif

            <x-input type="email" wire:model.defer="inputs.email" label="app.label.email" autofocus/>

            <div class="flex flex-col gap-2">
                <x-input type="password" wire:model.defer="inputs.password" label="app.label.password"/>
    
                @if (app('route')->has('password.forgot'))
                    <x-anchor label="app.label.forgot-password" :href="route('password.forgot')" class="text-sm"/>
                @endif
            </div>
        </x-fieldset>

        <x-slot:foot>
            <x-button action="submit" label="app.label.login" icon="login" color="theme" block lg/>
            
            @if (model('setting')->getSocialLogins()->count())
                <x-divider label="or"/>
                <x-button-social lg/>
            @endif
        </x-slot:foot>
    </x-form>

    @if (app('route')->has('register'))
        <div class="inline-flex item-center gap-2 px-4">
            {{ tr('app.label.dont-have-account') }} 
            <x-anchor label="app.label.signup-now" :href="route('register', ['utm' => 'page-login'])"/>
        </div>
    @endif
</div>
