<atom:modal name="app.shareable" wire:open="open" class="max-w-screen-sm">
    <div class="space-y-6">
        <atom:_heading size="xl">@t('share')</atom:_heading>

        <atom:toggle wire:model="enabled">@t('enable-sharing')</atom:toggle>

        @if ($enabled)
            <atom:_field>
                <atom:_label>@t('share-link')</atom:_label>
                <atom:_input :value="$shareable->url" readonly copyable/>

                <div class="flex items-center justify-between">
                    <atom:link icon="refresh" class="text-sm" wire:click="regenerate">@t('regenerate')</atom:link>
                    <atom:link icon="eye" class="text-sm" :href="$shareable->url" newtab>@t('preview')</atom:link>
                </div>
            </atom:_field>

            <div>
                <div class="md:w-1/2">
                    <atom:_input type="number" wire:model.lazy="shareable.valid_for" label="valid-for" suffix="day(s)"/>
                </div>

                @if ($shareable->expired_at)
                    <div class="text-sm text-muted">
                        @t('shared-link-will-expired-on', ['date' => $shareable->expired_at->pretty()])
                    </div>
                @endif
            </div>

            <atom:separator>@t('or-share-to')</atom:separator>

            <atom:group type="buttons" gap>
                <atom:_button
                    :social="[
                        'name' => 'whatsapp',
                        'text' => $shareable->url,
                    ]">Whatsapp
                </atom:_button>

                <atom:_button
                    :social="[
                        'name' => 'telegram',
                        'url' => $shareable->url,
                    ]">Telegram
                </atom:_button>

                @if ($mailable)
                    <atom:_button action="mail" icon="at" label="email">@t('email')</atom:_button>
                @endif
            </atom:group>
        @endif
    </div>
</atom:modal>
