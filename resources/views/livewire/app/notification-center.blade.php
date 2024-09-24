<x-drawer wire:close="$emit('closeNotificationCenter')">
    <x-slot:heading title="app.label.notification-center"></x-slot:heading>

    <div
        x-data="{
            total: 0,
            bubbles: [],

            init () {
                this.count()
                this.listen()
            },

            count () {
                return this.$wire.count().then(n => this.total = n)
            },

            listen () {
                Echo
                .private(`notification-center.{{ Js::from(user('id')) }}`)
                .listen('.notification-created', (notification) => this.toast(notification))
            },

            toast (notification) {
                this.count()
                if (this.bubbles.length >= 4) this.bubbles.pop()
                this.bubbles.prepend(notification)

                setTimeout(() => {
                    let index = this.bubbles.findIndex(item => (item.id === notification.id))
                    this.bubbles.splice(index, 1)
                }, 4000)
            },
        }"
        x-init="count()"
        x-on:notification-center-count.window="count()">
        <template x-teleport="#notification-center">
            <div
                x-on:click="() => {
                    Livewire.emit('showNotificationCenter')
                    $dispatch('notification-center-count')
                }"
                class="flex items-center justify-center cursor-pointer">
                <x-icon notification size="18">
                    <x-slot:badge
                        x-text="total"
                        x-show="total > 0"
                        class="text-red-100 bg-red-500">
                    </x-slot:badge>
                </x-icon>
            </div>
        </template>

        <template x-teleport="body">
            <div x-show="bubbles.length" class="fixed bottom-10 right-5 max-w-sm w-full flex flex-col gap-2" style="z-index: 999">
                <template x-for="item in bubbles" hidden>
                    <div
                        x-on:click.stop="item.href && window.href(item.href)"
                        x-bind:class="item.href && 'cursor-pointer'"
                        class="py-3 px-4 rounded-lg shadow-lg bg-black/80 text-sm">
                        <div class="flex items-center gap-2">
                            <div x-text="item.sender" class="grow font-medium text-gray-100 truncate"></div>
                            <div x-text="item.timestamp" class="shrink-0 text-gray-300"></div>
                        </div>
                        <div x-text="item.title" x-show="item.title" class="font-medium text-gray-100"></div>
                        <div x-text="item.content" class="text-gray-300"></div>
                    </div>
                </template>
            </div>
        </template>
    </div>

    <div class="p-5 flex flex-col gap-5">
        <x-tabs wire:model="tab">
            <x-tab value="unread" label="app.label.unread"/>
            <x-tab value="read" label="app.label.read"/>
            <x-tab value="archived" label="app.label.archived"/>
        </x-tabs>

        <div x-data="{
            select (notification) {
                if (!notification.read_at) this.read(notification).then(() => this.openUrl(notification.href))
                else this.openUrl(notification.href)
            },

            read ({ id }) {
                return this.$wire.read(id, true).then(() => this.dispatch())
            },

            unread ({ id }) {
                return this.$wire.read(id, false).then(() => this.dispatch())
            },

            archive ({ id }) {
                return this.$wire.archive(id, true).then(() => this.dispatch())
            },

            unarchive ({ id }) {
                return this.$wire.archive(id, false).then(() => this.dispatch())
            },

            openUrl (url) {
                if (!url) return
                window.href(url)
            },

            dispatch () {
                $el.dispatchEvent(new CustomEvent('notification-center-count', { bubbles: true }))
            },
        }" class="flex flex-col">
            @forelse ($notifications as $row)
                <div
                    wire:key="notification-{{ get($row, 'id') }}"
                    x-on:click.stop="select({{ Js::from($row) }})"
                    class="group relative -mx-2 p-4 flex flex-col gap-2 rounded-lg hover:bg-slate-50 cursor-pointer">
                    <div class="absolute top-4 right-4 items-center border rounded-md divide-x bg-white hidden group-hover:flex">
                        @if (get($row, 'archived_at'))
                            <div
                                x-tooltip.raw="{{ tr('app.label.restore') }}"
                                x-on:click.stop="unarchive({{ Js::from($row) }})"
                                class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                <x-icon unarchive/>
                            </div>
                        @else
                            @if (get($row, 'read_at'))
                                <div
                                    x-tooltip.raw="{{ tr('app.label.mark-unread') }}"
                                    x-on:click.stop="unread({{ Js::from($row) }})"
                                    class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                    <x-icon chat-unread/>
                                </div>
                            @else
                                <div
                                    x-tooltip.raw="{{ tr('app.label.mark-read') }}"
                                    x-on:click.stop="read({{ Js::from($row) }})"
                                    class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                    <x-icon double-check/>
                                </div>
                            @endif

                            <div
                                x-tooltip.raw="{{ tr('app.label.archive') }}"
                                x-on:click.stop="archive({{ Js::from($row) }})"
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
                            {!! $title !!}
                        </div>
                    @endif

                    <div class="text-gray-500 editor-content editor-chat-content">
                        {!! get($row, 'content') !!}
                    </div>
                </div>
            @empty
                <x-no-result/>
            @endforelse
        </div>
    </div>
</x-drawer>