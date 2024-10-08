<atom:modal variant="slide" class="max-w-lg">
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
                .private(`notification.{{ Js::from(user('id')) }}`)
                .listen('.notification-created', (notification) => {
                    this.count()
                    this.toast(notification)
                })
            },

            toast (notification) {
                Atom.toast({
                    title: notification.title,
                    message: notification.content,
                    user: notification.sender,
                })
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
                <atom:icon notification size="18"/>
                <span x-text="total" x-show="total > 0" class="bg-red-500 text-red-100 text-xs w-5 h-5 rounded-full flex items-center justify-center -ml-2"></span>
            </div>
        </template>
    </div>

    <div class="space-y-6">
        <atom:_heading size="lg">@t('notification-center')</atom:_heading>

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
                Atom.goto(url)
            },

            dispatch () {
                $el.dispatchEvent(new CustomEvent('notification-center-count', { bubbles: true }))
            },
        }" class="space-y-4">
            @forelse ($notifications as $row)
                <div
                    wire:key="notification-{{ get($row, 'id') }}"
                    x-on:click.stop="select({{ Js::from($row) }})"
                    class="group relative py-2 px-4 space-y-2 bg-slate-50 border rounded-lg cursor-pointer">
                    <div class="absolute top-2 right-2 items-center border rounded-md divide-x bg-white hidden group-hover:flex">
                        @if (get($row, 'archived_at'))
                            <div
                                x-tooltip="{{ js(t('restore')) }}"
                                x-on:click.stop="unarchive({{ Js::from($row) }})"
                                class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                <x-icon unarchive/>
                            </div>
                        @else
                            @if (get($row, 'read_at'))
                                <div
                                    x-tooltip="{{ js(t('mark-unread')) }}"
                                    x-on:click.stop="unread({{ Js::from($row) }})"
                                    class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                    <x-icon chat-unread/>
                                </div>
                            @else
                                <div
                                    x-tooltip="{{ js(t('mark-read')) }}"
                                    x-on:click.stop="read({{ Js::from($row) }})"
                                    class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                    <x-icon double-check/>
                                </div>
                            @endif

                            <div
                                x-tooltip="{{ js(t('archive')) }}"
                                x-on:click.stop="archive({{ Js::from($row) }})"
                                class="shrink-0 p-2 flex items-center justify-center cursor-pointer">
                                <x-icon archive/>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="grow flex items-center gap-2">
                            <div class="shrink-0 w-5 h-5 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center text-xs leading-none border">
                                {{ substr(get($row, 'sender.name'), 0, 1) }}
                            </div>
                            <div class="grow font-medium text-sm text-gray-500">
                                {{ get($row, 'sender.name') }}
                            </div>
                        </div>

                        <div class="shrink-0 text-sm text-gray-500 group-hover:hidden">
                            {{ get($row, 'timestamp') }}
                        </div>
                    </div>

                    <div>
                        @if ($title = get($row, 'title'))
                            <div class="font-medium">
                                {!! str()->limit($title, 80) !!}
                            </div>
                        @endif
    
                        <div class="text-gray-500">
                            {!! str()->limit(strip_tags(get($row, 'content')), 100) !!}
                        </div>
                    </div>
                </div>
            @empty
                <x-no-result/>
            @endforelse
        </div>
    </div>
</atom:modal>