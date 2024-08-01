<x-drawer wire:close="$emit('closeSendmail')" class="max-w-screen-md">
@if ($sendmail?->exists)
    <x-slot:heading title="app.label.sent-mail"></x-slot:heading>

    <x-slot:buttons>
        <x-button action="delete" invert no-label/>
    </x-slot:buttons>

    <div class="p-5 flex flex-col gap-5">
        @if ($error = $sendmail->getJson('data.error'))
            <x-inform type="error" message="{!! $error !!}"/>
        @endif

        <x-box>
            <x-fieldset>
                @foreach (['from', 'to', 'reply_to', 'cc', 'bcc', 'tags'] as $field)
                    <x-field :label="str()->headline($field)"
                        :badges="collect($sendmail->getJson('data.'.$field))->filter()->map(fn($val) => [
                            'color' => 'gray', 'label' => $val,
                        ])->all()">
                    </x-field>
                @endforeach

                @if ($attachments = $sendmail->getJson('data.attachments'))
                    <x-field label="app.label.attachment">
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach ($attachments as $attachment)
                                <div class="shrink-0">
                                    <x-badge icon="paperclip" :label="get($attachment, 'name')"/>
                                </div>
                            @endforeach
                        </div>
                    </x-field>
                @endif

                <x-field label="app.label.status" :status="[$sendmail->status->badge()]"/>
                <x-field label="app.label.subject" :value="$sendmail->subject"/>

                <div class="overflow-auto">
                    {!! $sendmail->getJson('data.body') !!}
                </div>
            </x-fieldset>
        </x-box>
    </div>
@endif
</x-drawer>