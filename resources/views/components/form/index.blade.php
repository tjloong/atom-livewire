@php
$box = $attributes->get('no-box') ? false : $attributes->get('box', true);
$submit = $attributes->get('submit', 'submit');
$confirm = $attributes->get('confirm');
$heading = $title ?? $heading ?? $attributes->get('title') ?? $attributes->get('heading');
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
    @if ($attributes->wire('loading')->value())
    wire:loading.class="is-loading"
    wire:target="{{ $submit }}"
    @endif
    class="group/form relative">
    <div class="absolute inset-0 hidden group-[.is-loading]/form:block {{ $box ? 'bg-white opacity-30' : '' }}">
        <div class="absolute top-4 right-4">
            <x-spinner size="20"/>
        </div>
    </div>

    <div class="{{ $box ? 'bg-white border rounded-xl shadow-sm' : null }}">
        @if ($heading instanceof \Illuminate\View\ComponentSlot)
            <x-heading class="p-4 mb-0" :attributes="$heading->attributes">
                {{ $heading }}
            </x-heading>
        @elseif ($heading)
            <x-heading class="p-4 mb-0" :title="$heading"/>
        @endif

        <div {{ $attributes->only('class') }}>
            {{ $slot }}
        </div>

        <div class="bg-gray-100 p-4 rounded-b-xl">
            @if (isset($foot) && $foot->isNotEmpty())
                {{ $foot }}
            @else
                <x-button action="submit"/>
            @endif
        </div>
    </div>
</form>
