@php
$filters = $attributes->get('filters') ?? [];

if (is_string($attributes->get('options'))) {
    $options = [];
    $callback = $attributes->get('options');
}
else {
    $options = $attributes->get('options') ?? [];
    $callback = null;
}
@endphp

<div
    x-data="{
        show: false,
        props: null,
        timer: null,
        pointer: 0,
        options: {{ Js::from($options) }},
        filters: {{ Js::from($filters) }},
        callback: {{ Js::from($callback) }},
        filteredOptions: [],

        init () {
            // assign the methods to the element so it can be called by tiptap using element.method()
            this.$el.start = (props) => this.start(props)
            this.$el.update = (props) => this.update(props)
            this.$el.exit = (props) => this.exit(props)
            this.$el.keydown = (props) => (this.keydown(props))
        },

        start (props) {
            this.show = true
            this.props = props
            this.pointer = 0
            this.fetch()
        },

        update (props) {
            this.props = props
            this.fetch()
        },

        exit (props) {
            this.show = false
            this.props = null
            this.filteredOptions = []
        },

        keydown (props) {
            if (props.event.key === 'Escape') {
                this.exit()
                return true
            }
            else if (props.event.key === 'Enter' && this.filteredOptions.length) {
                props.event.preventDefault()
                props.event.stopPropagation()
                if (this.pointer > -1) this.select(this.filteredOptions[this.pointer])
                else this.select(this.filteredOptions[0])
                return true
            }
            else if (props.event.key === 'ArrowUp' && this.filteredOptions.length) {
                this.arrowUp()
                return true
            }
            else if (props.event.key === 'ArrowDown' && this.filteredOptions.length) {
                this.arrowDown()
                return true
            }

            return false
        },

        arrowUp () {
            this.pointer = ((this.pointer + this.filteredOptions.length) - 1) % this.filteredOptions.length
        },

        arrowDown () {
            this.pointer = (this.pointer + 1) % this.filteredOptions.length
        },

        align () {
            let dropdown = this.$refs.dropdown
            let anchor = this.props.clientRect()
            dropdown.style.left = `${+anchor.left}px`
            dropdown.style.top = `${+anchor.top - dropdown.clientHeight - 10}px`
        },

        fetch () {
            this.pointer = 0

            if (this.callback) {
                clearTimeout(this.timer)
                this.timer = setTimeout(() => {
                    return ajax('/__select')
                        .post({
                            name: this.callback,
                            filters: { ...this.filters, search: this.props.query },
                        })
                        .then(res => this.filteredOptions = [...res])
                        .then(() => this.$nextTick(() => this.align()))
                }, 300)
            }
            else {
                this.filteredOptions = options.filter(opt => {
                    let searchable = typeof opt === 'object'
                        ? opt.searchable || `${opt.label} ${opt.small} ${opt.caption}`.trim().toLowerCase()
                        : opt.toString()
    
                    return searchable.includes(this.props.query.toLowerCase())
                })

                this.$nextTick(() => this.align())
            }
        },

        select (opt) {
            if (typeof opt === 'string') this.props.command({ id: opt })
            else {
                this.props.command({
                    id: opt.id,
                    label: opt.mention_render || opt.label || opt.value,
                })
            }

            this.exit()
        },
    }"
    class="editor-mention">
    <template x-teleport="body">
        <div
            x-ref="dropdown"
            x-on:keydown.up.prevent="arrowUp()"
            x-on:keydown.down.prevent="arrowDown()"
            class="absolute top-0 left-0 max-w-lg rounded-lg border shadow-lg z-10 bg-white">
            <div class="flex flex-col divide-y max-h-[300px] overflow-auto">
                <template x-for="(opt, i) in filteredOptions" hidden>
                    <div
                        x-on:mouseover="pointer = i"
                        x-on:click="select(opt)"
                        x-bind:class="pointer === i && 'bg-slate-100'"
                        class="py-2 px-4 cursor-pointer">
                        @if ($slot->isNotEmpty())
                            {{ $slot }}
                        @else
                            <div x-text="opt"></div>
                        @endif
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>