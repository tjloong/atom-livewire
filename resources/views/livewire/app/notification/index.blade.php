<div x-init="@js($notificationId) && $wire.emit('showNotification', @js($notificationId))" class="max-w-screen-xl mx-auto">
    <x-heading title="{{ [
        'mail' => 'app.label.sent-mail:2',
    ][$channel] }}" 2xl/>
    @livewire('app.notification.listing', compact('channel'), key('listing'))
</div>