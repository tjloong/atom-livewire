<div x-data class="shrink-0 py-3 px-4 bg-yellow-100 border-b border-yellow-200">
    <div class="flex flex-wrap items-center gap-2">
        <x-icon name="triangle-exclamation" class="shrink-0 text-yellow-400"/>
        <div class="font-medium text-yellow-600">
            {{ __('We have sent a verification link to :email, please click on the link to verify it.', [
                'email' => user('email'),
            ]) }}
        </div>
        <a href="{{ route('verification.send') }}" class="text-sm">
            {{ __('Resend verification link') }}
        </a>
    </div>
</div> 
