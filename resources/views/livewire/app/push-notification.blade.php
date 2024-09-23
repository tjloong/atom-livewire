<x-drawer wire:close="$emit('closePushNotifications')">
    <x-slot:heading title="app.label.notifications"></x-slot:heading>

    <div class="p-5 flex flex-col gap-5">
        <x-tabs wire:model="tab">
            <x-tab value="unread" label="app.label.unread"/>
            <x-tab value="read" label="app.label.read"/>
            <x-tab value="archived" label="app.label.archived"/>
        </x-tabs>

        <div class="flex flex-col">
            @forelse ($notifications as $row)
                <div
                    x-data="{ url: {{ Js::from(get($row, 'href')) }} }"
                    @if (get($row, 'read_at'))
                    x-on:click.stop="url && href(url)"
                    @else
                    x-on:click.stop="$wire.read({{ Js::from(get($row, 'id')) }}, true).then(() => url && href(url))"
                    @endif
                    wire:key="notification-{{ get($row, 'id') }}"
                    class="group relative -mx-2 p-4 flex flex-col gap-2 rounded-lg hover:bg-slate-50 cursor-pointer">
                    <div class="absolute top-4 right-4 items-center border rounded-md divide-x bg-white hidden group-hover:flex">
                        @if (get($row, 'read_at'))
                            <div
                                x-tooltip.raw="{{ tr('app.label.mark-unread') }}"
                                wire:click.stop="read({{ Js::from(get($row, 'id')) }}, false)"
                                class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                <x-icon chat-unread/>
                            </div>
                        @else
                            <div
                                x-tooltip.raw="{{ tr('app.label.mark-read') }}"
                                wire:click.stop="read({{ Js::from(get($row, 'id')) }})"
                                class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                <x-icon double-check/>
                            </div>
                        @endif

                        @if (get($row, 'archived_at'))
                            <div
                                x-tooltip.raw="{{ tr('app.label.restore') }}"
                                wire:click.stop="archive({{ Js::from(get($row, 'id')) }}, false)"
                                class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                <x-icon unarchive/>
                            </div>
                        @else
                            <div
                                x-tooltip.raw="{{ tr('app.label.archive') }}"
                                wire:click.stop="archive({{ Js::from(get($row, 'id')) }})"
                                class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                <x-icon archive/>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="grow flex items-center gap-2">
                            <div class="shrink-0">
                                <x-avatar-bullets :avatars="[get($row, 'sender')]"/>
                            </div>
                            <div class="grow font-medium">
                                {{ get($row, 'sender.name') }}
                            </div>
                        </div>

                        <div class="shrink-0 text-sm text-gray-500 group-hover:hidden">
                            {{ get($row, 'timestamp') }}
                        </div>
                    </div>

                    @if ($title = get($row, 'title'))
                        <div class="font-medium">
                            {!! str()->limit($title, 100) !!}
                        </div>
                    @endif

                    <div class="text-gray-500">
                        {!! str()->limit(get($row, 'content'), 100) !!}
                    </div>
                </div>
            @empty
                <x-no-result/>
            @endforelse
        </div>
    </div>
</x-drawer>