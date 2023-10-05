<div>
    <x-button.social-login
        size="md"
        divider="OR"
        divider-position="bottom"/>

    <div class="flex flex-col gap-4">
        <x-form>
            <x-form.group>
                <div class="text-2xl font-bold text-gray-600">
                    {{ __('atom::auth.heading.create-account') }}
                </div>
            
                <x-form.text wire:model.defer="inputs.name" label="atom::auth.label.your-name" autofocus/>

                @if ($utm === 'invitation')
                    <x-form.field label="atom::auth.label.login-email">
                        {{ data_get($inputs, 'email') }}
                    </x-form.field>
                @else
                    <x-form.email wire:model.defer="inputs.email" label="atom::auth.label.login-email"/>
                @endif
                
                <x-form.password wire:model.defer="inputs.password" label="atom::auth.label.login-password"/>
            
                <div class="grid gap-2">
                    <x-form.checkbox.privacy wire:model="inputs.agree_tnc"/>
                    <x-form.checkbox.marketing wire:model="inputs.agree_promo"/>
                </div>
            </x-form.group>

            <x-slot:foot>
                @if (!$errors->any() && $this->verification)
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-1">
                            <x-form.text caption="atom::auth.caption.email-verification-code"
                                wire:model.defer="inputs.verification"/>
    
                            <div x-data="{
                                show: true,
                                timer: null,
                                minutes: null,
                                seconds: null,
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
                            }">
                                <x-link label="atom::auth.label.resend" class="text-sm"
                                    x-show="show"
                                    x-on:click.stop="resend()"/>

                                <span 
                                    x-show="!show && (minutes || seconds)"
                                    x-text="`${minutes}:${seconds}`"
                                    class="text-sm text-gray-500 font-medium"></span>
                            </div>
                        </div>

                        <x-button.submit md
                            label="atom::auth.button.create-account"/>
                    </div>
                @else
                    <x-button.submit md block
                        label="atom::auth.button.create-account"/>
                @endif
            </x-slot:foot>
        </x-form>
        
        <div class="text-center">
            {{ __('atom::auth.caption.have-account') }} <x-link label="atom::auth.label.signin" :href="route('login')"/>
        </div>
    </div>
</div>

