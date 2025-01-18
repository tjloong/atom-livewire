<div>

    <div class="space-y-6">
        <atom:card>
            <atom:_form x-recaptcha:submit.register.prevent="() => $wire.submit()">
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

                <atom:_button action="submit" variant="primary" wire:loading block>
                    @t('create-account')
                </atom:_button>
            </atom:_form>
        </atom:card>

        <atom:social-logins separator-top="OR"/>

        <div class="text-center">
            @t('have-account')
            <atom:link :href="route('login')">@t('signin')</atom:link>
        </div>
    </div>

    <atom:modal name="passcode" :closeable="false" locked>
        <div
            x-data="{
                timer: null,
                minutes: null,
                seconds: null,
                loading: false,
                passcode: @entangle('passcode').defer,

                init () {
                    this.$watch('passcode', () => {
                        if (this.passcode?.length >= 6) this.verify()
                    })
                },

                verify () {
                    this.loading = true
                    this.$wire.verify(this.passcode).then(() => this.loading = false)
                },

                resend () {
                    this.loading = true
                    this.$wire.resend().then(() => this.countdown()).then(() => this.loading = false)
                },

                countdown () {
                    if (this.timer === null) this.timer = 120 * 1000
                    if (this.timer <= 0) return

                    setTimeout(() => {
                        this.timer = this.timer - 1000
                        this.minutes = Math.floor(this.timer / 60000)
                        this.seconds = ((this.timer % 60000) / 1000).toFixed(0)
                        this.countdown()
                    }, 1000)
                },
            }"
            class="space-y-1">
            <atom:_input field="passcode"
                x-model.debounce.500="passcode"
                x-bind:readonly="loading"
                caption="we-have-sent-a-verification-code-to-your-email"
                label="verification-code">
            </atom:_input>

            <template x-if="loading" hidden>
                <atom:icon loading/>
            </template>

            <template x-if="!loading" hidden>
                <div class="text-sm">
                    <atom:link x-show="!timer" x-on:click.stop="resend()">@t('resend')</atom:link>

                    <span 
                        x-show="timer && (minutes || seconds)"
                        x-text="`${minutes}:${seconds}`"
                        class="text-gray-500 font-medium">
                    </span>
                </div>
            </template>
        </div>
    </atom:model>
</div>
