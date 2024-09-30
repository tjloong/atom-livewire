<div class="space-y-6">
    @if (!$this->verification && $this->socialLogins->count())
        <div class="space-y-3">
            @foreach ($this->socialLogins as $item)
                <atom:_button variant="default" :social="$item" block>
                    @t('continue-with-social-login', ['provider' => get($item, 'label')])
                </atom:_button>
            @endforeach
        </div>
        <atom:separator>OR</atom:separator>
    @endif

    <atom:card>
        <atom:_form x-recaptcha:submit.register.prevent="() => $wire.submit()">
            @if (!$errors->any() && $this->verification)
                <atom:_heading size="20">@t('email-verification')</atom:_heading>

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
                            class="text-gray-500 font-medium">
                        </span>
                    </div>
                </div>
            @else
                <atom:_heading size="20">@t('create-account')</atom:_heading>

                <x-input wire:model.defer="inputs.name" label="app.label.your-name" autofocus/>
                <x-input type="email" wire:model.defer="inputs.email" label="app.label.login-email"/>
                <x-input type="password" wire:model.defer="inputs.password" label="app.label.login-password"/>

                <x-checkbox wire:model="inputs.agree_tnc" label="app.label.checkbox-privacy">
                    <div class="inline-flex items-center gap-3 flex-wrap text-sm">
                        <x-anchor label="app.label.terms-of-use" href="/terms"/>
                        <x-anchor label="app.label.privacy-policy" href="/privacy"/>
                    </div>
                </x-checkbox>

                <x-checkbox wire:model="inputs.agree_promo" label="app.label.checkbox-marketing"/>
            @endif

            <atom:_button action="submit" variant="primary" wire:loading block>
                @t('create-account')
            </atom:_button>
        </atom:_form>
    </atom:card>

    <div class="text-center">
        @t('have-account')
        <x-anchor label="app.label.signin" :href="route('login')"/>
    </div>
</div>
