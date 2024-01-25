<x-drawer id="notification-show" wire:close="close()">
@if (optional($notification)->exists)
    <x-slot:heading title="{{ [
        'mail' => 'app.label.sent-mail',
    ][$notification->channel] }}" :status="$notification->status->badge()"></x-slot:heading>
    <x-slot:buttons delete></x-slot:buttons>

    <x-form.group>
        <x-box>
            <div class="flex flex-col divide-y">
                <x-field label="app.label.notification-channel" :value="$notification->channel"/>

                @if ($notification->channel === 'mail')
                    <x-field label="app.label.notification-subject" :value="$notification->subject"/>
                    <x-field label="app.label.notification-from" :value="collect($notification->getJson('data.from'))->filter()->join(', ')"/>
                    <x-field label="app.label.notification-to" :value="collect($notification->getJson('data.to'))->keys()->filter()->join(', ')"/>
                    <x-field label="app.label.notification-reply-to" :value="collect($notification->getJson('data.reply_to'))->filter()->join(', ')"/>
                    <x-field label="app.label.notification-cc" :value="collect($notification->getJson('data.cc'))->filter()->join(', ')"/>
                    <x-field label="app.label.notification-bcc" :value="collect($notification->getJson('data.cc'))->filter()->join(', ')"/>
                    <x-field label="app.label.notification-error" :value="$notification->getJson('data.error')"/>
                @endif
            </div>
        </x-box>
    </x-form.group>

    @if ($notification->channel === 'mail')
        <x-form.group heading="app.label.notification-body">
            <x-box>
                {!! $notification->body !!}
            </x-box>
        </x-form.group>
    @endif
@endif
</x-drawer>