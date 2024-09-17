@php
$options = $attributes->get('options') ?? [];
$filters = $attributes->get('filters') ?? [];
@endphp

<div
    x-data="{
        show: false,
        items: [],
        props: null,
        pointer: 0,

        init () {
            // assign the methods to the element so it can be called by tiptap using element.method()
            this.$el.start = (props) => this.start(props)
            this.$el.update = (props) => this.update(props)
            this.$el.exit = () => this.exit()
            this.$el.keydown = (props) => (this.keydown(props))
            this.$el.fetch = (query) => (this.fetch(query))
        },

        start (props) {
            this.show = true
            this.props = props
            this.items = [...props.items]
            this.pointer = 0
            this.$nextTick(() => this.align())
        },

        update (props) {
            this.props = props
            this.items = [...props.items]
            this.$nextTick(() => this.align())
        },

        keydown (props) {
            if (props.event.key === 'Escape') {
                this.exit()
                return true
            }
            else if (props.event.key === 'Enter' && this.items.length) {
                if (this.pointer > -1) this.select(this.items[this.pointer])
                else this.select(this.items[0])
                return true
            }
            else if (props.event.key === 'ArrowUp' && this.items.length) {
                this.arrowUp()
                return true
            }
            else if (props.event.key === 'ArrowDown' && this.items.length) {
                this.arrowDown()
                return true
            }

            return false
        },

        arrowUp () {
            this.pointer = ((this.pointer + this.items.length) - 1) % this.items.length
        },

        arrowDown () {
            this.pointer = (this.pointer + 1) % this.items.length
        },

        exit () {
            this.show = false
            this.props = null
            this.items = []
        },

        align () {
            let dropdown = this.$refs.dropdown
            let anchor = this.props.clientRect()
            dropdown.style.left = `${+anchor.left}px`
            dropdown.style.top = `${+anchor.top - dropdown.clientHeight - 10}px`
        },

        fetch (query) {
            let options = {{ Js::from($options) }}
            let filters = {{ Js::from($filters) }}

            let search = (items) => (items.filter(item => {
                let searchable = typeof item === 'object'
                    ? item.searchable || `${item.label} ${item.small} ${item.caption}`.trim().toLowerCase()
                    : item.toString()

                return searchable.includes(query.toLowerCase())
            }))

            if (typeof options === 'string') {
                return ajax('/__select').post({
                    name: options,
                    filters: filters,
                }).then(res => (search(res)))
            }
            else {
                return search(options)
            }
        },

        select (item) {
            this.props.command({ id: item })
            this.exit()
        },
    }"
    class="editor-mention">
    <template x-teleport="body">
        <div
            x-ref="dropdown"
            x-on:keydown.up.prevent="arrowUp()"
            x-on:keydown.down.prevent="arrowDown()"
            class="absolute top-0 left-0 max-w-40 rounded-lg border shadow-lg z-10 bg-white">
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                <div class="flex flex-col divide-y">
                    <template x-for="(item, i) in items" hidden>
                        <div
                            x-on:mouseover="pointer = i"
                            x-on:click="select(item)"
                            x-bind:class="pointer === i && 'bg-slate-100'"
                            class="py-2 px-4">
                            <div x-text="item"></div>
                        </div>
                    </template>
                </div>
            @endif
        </div>
    </template>
</div>