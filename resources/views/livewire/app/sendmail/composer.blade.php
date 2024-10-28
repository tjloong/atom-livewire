<atom:modal name="app.sendmail.composer" variant="slide" wire:open="open" wire:close="cleanup">
@if ($email)
    <atom:_form>
        <atom:_heading size="xl">@t('send-email')</atom:_heading>

        <atom:_input type="email" wire:model.defer="email.sender_email" label="sender-email"/>
        <atom:_input wire:model.defer="email.sender_name" label="sender-name"/>
        <atom:_input type="email" wire:model.defer="email.reply_to" label="reply-to"/>
        <atom:_input type="email" wire:model.defer="email.to" label="to" :options="get($email, 'email_options')"/>
        <atom:_input type="email" wire:model.defer="email.cc" label="cc" :options="get($email, 'email_options')"/>
        <atom:_input type="email" wire:model.defer="email.bcc" label="bcc" multiple/>
        <atom:_input wire:model.defer="email.subject" label="subject"/>
        <atom:_textarea wire:model.defer="email.body" label="body" rows="10" autoresize/>

        <atom:_field>
            <atom:_label>@t('attachment')</atom:_label>
            <div class="flex items-center gap-2 flex-wrap">
                @foreach (get($email, 'attachments') as $i => $attachment)
                    <div
                        wire:key="{{ get($attachment, 'id') }}"
                        class="h-10 max-w-72 border border-zinc-200 rounded-md bg-white shadow-sm flex items-center gap-3 px-3">
                        <atom:icon attach class="shrink-0 text-muted-more"/>
                        <div class="grow truncate">@ee(get($attachment, 'filename'))</div>
                        <div
                            wire:click="detach({{ $i }})"
                            class="shrink-0 text-muted-more flex items-center justify-center">
                            <atom:icon delete/>
                        </div>
                    </div>
                @endforeach

                <div
                    x-data
                    x-on:click="$refs.file.click()"
                    x-tooltip="{{ js(t('attach')) }}"
                    class="h-10 w-10 rounded-md border border-dashed border-zinc-300 flex items-center justify-center">
                    <atom:icon attach/>
                    <input type="file" x-ref="file" wire:model="uploads" x-on:click.stop class="hidden" multiple>
                </div>
            </div>
        </atom:_field>

        <atom:_button action="submit">@t('send')</atom:_button>
    </atom:_form>
@else
    <atom:skeleton/>
@endif
</atom:modal>