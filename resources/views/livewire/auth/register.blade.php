<div class="flex flex-col gap-4">
    @if (!$this->verification && model('setting')->getSocialLogins()->count())
        <x-button-social/>
        <x-divider label="or"/>
    @endif

    @recaptcha

    <x-form recaptcha="register">
        <x-group>
        @if (!$errors->any() && $this->verification)
            <x-heading title="app.label.email-verification" 2xl/>

            <div class="flex flex-col gap-1">
                <x-input wire:model.defer="inputs.verification" label="app.label.verification-code" caption="app.alert.email-verification"/>
        
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
                    },
                }" class="text-sm">
                    <x-anchor label="app.label.resend" x-show="show" x-on:click.stop="resend()"/>
        
                    <span 
                        x-show="!show && (minutes || seconds)"
                        x-text="`${minutes}:${seconds}`"
                        class="text-gray-500 font-medium"></span>
                </div>
            </div>
        @else
            <x-heading title="app.label.create-account" 2xl/>

            <x-input wire:model.defer="inputs.name" label="app.label.your-name" autofocus/>
            <x-input type="email" wire:model.defer="inputs.email" label="app.label.login-email"/>
            <x-input type="password" wire:model.defer="inputs.password" label="app.label.login-password"/>
        
            <div class="flex flex-col gap-3">
                <x-checkbox wire:model="inputs.agree_tnc" label="app.label.checkbox-privacy">
                    <div class="inline-flex items-center gap-3 flex-wrap text-sm">
                        <x-anchor label="app.label.terms-of-use"/>
                        <x-anchor label="app.label.privacy-policy"/>
                    </div>
                </x-checkbox>

                <x-checkbox wire:model="inputs.agree_promo" label="app.label.checkbox-marketing"/>
            </div>
        @endif
        </x-group>

        <x-slot:foot>
            <x-button action="submit" md block label="app.label.create-account"/>
        </x-slot:foot>
    </x-form>
    
    <div class="text-center">
        {{ tr('auth.label.have-account') }} <x-anchor label="auth.label.signin" :href="route('login')"/>
    </div>
</div>

