<div>
    <x-button.social-login divider="OR"/>
    <div class="flex flex-col gap-4">
        <x-form recaptcha="register">
            <x-group>
            @if (!$errors->any() && $this->verification)
            <x-heading title="app.label.email-verification" 2xl/>

                <div class="flex flex-col gap-1">
                    <x-form.text label="app.label.verification-code"
                        caption="auth.alert.email-verification"
                        wire:model.defer="inputs.verification"/>

                    <div x-data="{
                        show: true,
                        timer: null,
                        minutes: null,
                        seconds: null,
                        init () {
                            this.$el.scrollIntoView()
                        },
                        resend () {
                            this.show = false
                            this.$wire.sendVerificationCode().then(() => {
                                this.timer = 120 * 1000
                                this.countdown()
                            })
                        },
                        countdown () {
                            setTimeout(() => {
                                if (this.timer <= 0) {
                                    this.show = true
                                }
                                else {
                                    this.timer = this.timer - 1000
                                    this.minutes = Math.floor(this.timer / 60000)
                                    this.seconds = ((this.timer % 60000) / 1000).toFixed(0)
                                    this.countdown()
                                }
                            }, 1000)
                        }
                    }" class="text-sm">
                        <x-link label="app.label.resend" x-show="show" x-on:click.stop="resend()"/>

                        <span 
                            x-show="!show && (minutes || seconds)"
                            x-text="`${minutes}:${seconds}`"
                            class="text-gray-500 font-medium"></span>
                    </div>
                </div>
            @else
                <x-heading title="app.label.create-account" 2xl/>

                <x-form.text wire:model.defer="inputs.name" label="app.label.your-name" autofocus/>

                @if ($utm === 'invitation')
                    <x-form.field label="app.label.login-email">
                        {{ data_get($inputs, 'email') }}
                    </x-form.field>
                @else
                    <x-form.email wire:model.defer="inputs.email" label="app.label.login-email"/>
                @endif
                
                <x-form.password wire:model.defer="inputs.password" label="app.label.login-password"/>
            
                <div class="grid gap-2">
                    <x-form.checkbox.privacy wire:model="inputs.agree_tnc"/>
                    <x-form.checkbox.marketing wire:model="inputs.agree_promo"/>
                </div>
            @endif
            </x-group>

            <x-slot:foot>
                <x-button.submit md block label="app.label.create-account"/>
            </x-slot:foot>
        </x-form>
        
        <div class="text-center">
            {{ tr('auth.label.have-account') }} <x-link label="auth.label.signin" :href="route('login')"/>
        </div>
    </div>
</div>

