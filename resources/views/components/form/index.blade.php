@php
    $submit = $attributes->get('submit', 'submit');
    $confirm = $attributes->get('confirm');
    $recaptcha = [
        'sitekey' => $attributes->get('recaptcha') ? settings('recaptcha_site_key') : null,
        'action' => is_string($attributes->get('recaptcha')) ? $attributes->get('recaptcha') : 'submit',
    ];
@endphp

<form
    x-cloak
    x-data="{
        disabled: false,
        confirm: @js($confirm),
        recaptcha: @js($recaptcha),
        autofocus () {
            this.$nextTick(() => this.$el.querySelector('input[autofocus]')?.focus())
        },
        submit () {
            if (this.confirm) {
                $dispatch('confirm', {
                    title: @js(tr('common.alert.submit.title')),
                    message: @js(tr('common.alert.submit.message')),
                    onConfirmed: () => this.verify(),
                })
            }
            else this.verify()
        },
        verify () {
            if (this.recaptcha.sitekey && !this.$wire.get('form.recaptcha_token') && window.grecaptcha !== undefined) {
                this.disabled = true

                grecaptcha.ready(() => {
                    grecaptcha.execute(this.recaptcha.sitekey, { action: this.recaptcha.action })
                        .then(token => (this.$wire.set('form.recaptcha_token', token)))
                        .then(() => (this.$wire.call(@js($submit))))
                        .then(() => this.disabled = false)
                })
            }
            else this.$wire.call(@js($submit))
        },
    }"
    x-init="autofocus"
    x-on:submit.prevent="submit"
    class="relative">
    <div x-show="disabled" class="absolute inset-0"></div>
    <x-box :heading="$attributes->get('heading')" :icon="$attributes->get('icon')">
        @isset($heading)
            <x-slot:heading>
                {{ $heading }}
            </x-slot:heading>
        @endisset

        {{ $slot }}

        @isset($foot)
            @if ($foot->isNotEmpty())
                <x-slot:foot>
                    {{ $foot }}
                </x-slot:foot>
            @endif
        @else
            <x-slot:foot>
                <x-button.submit/>
            </x-slot:foot>
        @endif
    </x-box>
</form>
