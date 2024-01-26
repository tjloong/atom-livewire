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
                    <x-field label="app.label.notification-from" :badges="$notification->from"/>
                    <x-field label="app.label.notification-to" :badges="$notification->to"/>
                    <x-field label="app.label.notification-reply-to" :badges="$notification->reply_to"/>
                    <x-field label="app.label.notification-cc" :badges="$notification->cc"/>
                    <x-field label="app.label.notification-bcc" :badges="$notification->bcc"/>

                    @if ($attachments = $notification->getJson('data.attachments'))
                        <x-field label="app.label.attachment">
                            <div class="flex flex-wrap items-center gap-2">
                                @foreach ($attachments as $attachment)
                                    <div class="shrink-0">
                                        <x-badge icon="paperclip" :label="data_get($attachment, 'name')"/>
                                    </div>
                                @endforeach
                            </div>
                        </x-field>
                    @endif
                @endif
            </div>
        </x-box>

        @if ($error = $notification->getJson('data.error'))
            <x-alert type="error" message="{!! $error !!}"/>
        @endif
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