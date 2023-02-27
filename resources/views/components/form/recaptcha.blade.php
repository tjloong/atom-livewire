<div>
    <div wire:ignore>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
        <script>
            window.recaptchaCallback = (res) => {
                @this.set('{{ $attributes->wire('model')->value() }}', res)
            }
        </script>
    
        <div class="g-recaptcha" 
            data-sitekey="{{ $attributes->get('sitekey') 
                ?? env('RECAPTCHA_SITEKEY') 
                ?? settings('recaptcha_sitekey') 
            }}"
            data-callback="recaptchaCallback"
        ></div>
    </div>
    
    @if ($err = $attributes->get('error'))
        <div class="text-red-500 text-sm">
            {{ __($err) }}
        </div>
    @endif
</div>
