<div
wire:ignore.self
x-cloak
x-data
x-init="() => {
    Echo
    .private(`notification.{{ js(user('id')) }}`)
    .listen('.notification-created', (notification) => {
        Atom.toast({
            title: notification.title,
            message: notification.content,
            user: notification.sender,
        })

        $dispatch('notification-created', notification)
    })
}">
    <atom:modal name="atom.notification-center" wire:open="open" class="max-w-lg">
        <div class="space-y-6">
            <atom:_heading size="xl">
                @t('notification-center')
            </atom:_heading>

            <atom:tabs wire:model="tab">
                <atom:tab value="unread">@t('unread')</atom:tab>
                <atom:tab value="read">@t('read')</atom:tab>
                <atom:tab value="archived">@t('archived')</atom:tab>
            </atom:tabs>

            <div class="space-y-4">
                @forelse ($notifications as $row)
                    <atom:card inset
                        wire:key="notification-{{ get($row, 'id') }}"
                        x-on:click="$wire.read({{ js(get($row, 'id')) }}).then(() => Atom.goto({{ js(get($row, 'href')) }}))">
                        <div class="group relative p-4 space-y-2 cursor-pointer">
                            <div class="absolute top-4 right-4 items-center hidden group-hover:block">
                                <atom:group type="buttons">
                                    @if (get($row, 'archived_at'))
                                        <atom:_button icon="unarchive" size="sm" tooltip="restore"
                                            x-on:click.stop="$wire.archive({{ js(get($row, 'id')) }}, false)">
                                        </atom:_button>
                                    @else
                                        @if (get($row, 'read_at'))
                                            <atom:_button icon="chat-unread" size="sm" tooltip="mark-unread"
                                                x-on:click.stop="$wire.read({{ js(get($row, 'id')) }}, false)">
                                            </atom:_button>
                                        @else
                                            <atom:_button icon="double-check" size="sm" tooltip="mark-read"
                                                x-on:click.stop="$wire.read({{ js(get($row, 'id')) }}, true)">
                                            </atom:_button>
                                        @endif

                                        <atom:_button icon="archive" size="sm" tooltip="archive"
                                            x-on:click.stop="$wire.archive({{ js(get($row, 'id')) }}, true)">
                                        </atom:_button>
                                    @endif
                                </atom:group>
                            </div>
                                
                            <div class="flex items-center gap-2 text-muted">
                                <div class="grow flex items-center gap-2">
                                    <div class="shrink-0 w-5 h-5 rounded-full bg-zinc-200 flex items-center justify-center text-xs leading-none border">
                                        @e(substr(get($row, 'sender.name'), 0, 1))
                                    </div>

                                    <div class="grow font-medium text-sm">
                                        @e(get($row, 'sender.name'))
                                    </div>
                                </div>

                                <div class="shrink-0 text-sm group-hover:hidden">
                                    @e(get($row, 'timestamp'))
                                </div>
                            </div>

                            @if ($title = get($row, 'title'))
                                <div class="font-medium">
                                    @ee(str()->limit($title, 80))
                                </div>
                            @endif

                            <div class="text-muted">
                                @ee(str()->limit(strip_tags(get($row, 'content')), 100))
                            </div>
                        </div>
                    </atom:card>
                @empty
                    <atom:empty/>
                @endforelse
            </div>
        </div>
    </atom:modal>
</div>
