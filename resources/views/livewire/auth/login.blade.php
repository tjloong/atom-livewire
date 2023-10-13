<div class="flex flex-col gap-4">
    <form wire:submit.prevent="submit">
        <x-box>
            <x-form.group>
                <x-heading title="atom::auth.heading.login" 2xl/>
    
                @if ($flash = session('flash')) <x-alert>{{ __($flash) }}</x-alert> @endif
    
                <x-alert.errors/>
    
                <x-form.text :label="$this->usernameLabel" autofocus
                    wire:model.defer="inputs.username"/>
    
                <div>
                    <x-form.password label="atom::auth.label.password"
                        wire:model.defer="inputs.password"/>
        
                    @if (has_route('password.forgot'))
                        <x-link label="atom::auth.button.forgot-password"
                            :href="route('password.forgot')" 
                            class="text-theme text-sm"/>
                    @endif
                </div>
            </x-form.group>
    
            <x-slot:foot>
                <x-button.submit label="atom::auth.button.login" icon="login" color="theme" block lg/>
                <x-button.social-login divider="atom::auth.label.or-login-with"/>
            </x-slot:foot>
        </x-box>
    </form>

    @if (has_route('register'))
        <div class="inline-flex item-center gap-2 px-4">
            {{ __('atom::auth.label.dont-have-account') }} 
            <x-link label="atom::auth.button.signup-now" 
                :href="route('register', ['utm' => 'page-login'])"/>
        </div>
    @endif
</div>
