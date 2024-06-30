<x-modal.drawer wire:close="$emit('closeNotilog')">
@if (optional($notilog)->exists)
    <x-slot:heading title="app.label.outbox-log" :status="$notilog->status->badge()"></x-slot:heading>

    <x-slot:buttons>
        <x-button action="delete" invert no-label/>
    </x-slot:buttons>

    <div class="p-5 flex flex-col gap-5">
        <x-box>
            <x-fieldset>
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
            </x-fieldset>
        </x-box>

        @if ($error = $notilog->getJson('data.error'))
            <x-inform type="error" message="{!! $error !!}"/>
        @endif
    </div>

    @if ($notilog->channel === 'mail')
        <x-fieldset no-hover>
            <x-field label="app.label.subject" :value="$notilog->subject" block/>
            <x-field label="app.label.body" block>
                <x-box>{!! $notilog->body !!}</x-box>
            </x-field>
        </x-fieldset>
    @endif
@endif
</x-modal.drawer>