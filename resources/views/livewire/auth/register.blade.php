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
                    <atom:_input
                        wire:model.defer="inputs.verification"
                        caption="we-have-sent-a-verification-code-to-your-email"
                        label="verification-code">
                    </atom:_input>

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
                        <atom:link x-show="show" x-on:click.stop="resend()">@t('resend')</atom:link>

                        <span 
                            x-show="!show && (minutes || seconds)"
                            x-text="`${minutes}:${seconds}`"
                            class="text-gray-500 font-medium">
                        </span>
                    </div>
                </div>
            @else
                <atom:_heading size="20">@t('create-account')</atom:_heading>

                <atom:_input wire:model.defer="inputs.name" label="your-name" autofocus/>
                <atom:_input type="email" wire:model.defer="inputs.email" label="login-email"/>
                <atom:_input type="password" wire:model.defer="inputs.password" label="login-password"/>

                <atom:_checkbox wire:model="inputs.agree_tnc">
                    <div class="space-y-1">
                        <div>
                            @t('i-agree-for-privacy-policy')
                        </div>
                        <div class="inline-flex items-center gap-3 flex-wrap text-sm">
                            <atom:link href="/terms">@t('terms-of-use')</atom:link>
                            <atom:link href="/privacy">@t('privacy-policy')</atom:link>
                        </div>
                    </div>
                </atom:_checkbox>

                <atom:_checkbox wire:model="inputs.agree_promo">
                    @t('i-agree-for-future-marketing')
                </atom:_checkbox>
            @endif

            <atom:_button action="submit" variant="primary" wire:loading block>
                @t('create-account')
            </atom:_button>
        </atom:_form>
    </atom:card>

    <div class="text-center">
        @t('have-account')
        <atom:link :href="route('login')">@t('signin')</atom:link>
    </div>
</div>
