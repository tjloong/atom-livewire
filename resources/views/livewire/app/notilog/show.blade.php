<x-drawer wire:close="close()">
@if (optional($notilog)->exists)
    <x-slot:heading title="app.label.outbox-log" :status="$notilog->status->badge()"></x-slot:heading>
    <x-slot:buttons delete></x-slot:buttons>

    <x-form.group>
        <x-box>
            <div class="flex flex-col divide-y">
                <x-field label="app.label.channel" :value="$notilog->channel"/>

                @if ($notilog->channel === 'mail')
                    <x-field label="app.label.from" :badges="$notilog->from"/>
                    <x-field label="app.label.to" :badges="$notilog->to"/>
                    <x-field label="app.label.reply-to" :badges="$notilog->reply_to"/>
                    <x-field label="app.label.cc" :badges="$notilog->cc"/>
                    <x-field label="app.label.bcc" :badges="$notilog->bcc"/>
                    <x-field label="app.label.tag" :badges="$notilog->tags"/>

                    @if ($attachments = $notilog->getJson('data.attachments'))
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

        @if ($error = $notilog->getJson('data.error'))
            <x-alert type="error" message="{!! $error !!}"/>
        @endif
    </x-form.group>

    @if ($notilog->channel === 'mail')
        <x-form.group>
            <x-form.field label="app.label.subject" :value="$notilog->subject"/>
            <x-form.field label="app.label.body">
                <x-box>{!! $notilog->body !!}</x-box>
            </x-form.field>
        </x-form.group>
    @endif
@endif
</x-drawer>